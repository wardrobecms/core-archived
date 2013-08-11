# Post View
# ---------------
# A parent view which the add and edit views extend from.
@Wardrobe.module "Views", (Views, App, Backbone, Marionette, $, _) ->

  class Views.PostView extends App.Views.ItemView
    template: "post/_base/templates/form"
    className: "span12"

    initialize: ->
      # Set a flag so we know when the tags are shown.
      @tagsShown = false

    events:
      "click .publish" : "save"
      "click .js-toggle" : "toggleDetails"
      "click .icon-tags" : "toggleTags"
      "click .icon-user" : "showUsers"
      "change .js-active" : "changeBtn"
      "keyup #title" : "localStorage"

    # When the model changes it's private _errors method call the changeErrors method.
    modelEvents:
      "change:_errors"  : "changeErrors"

    templateHelpers:
      # Set the primary button text based on the model active status.
      submitBtnText: ->
        if @active? or @active is "1" then Lang.post_publish else Lang.post_save
      # Generate a preview url.
      previewUrl: ->
        "#{App.request("get:url:blog")}post/preview/#{@id}"

    # When the view is shown in the DOM setup all the plugins
    onShow: ->
      @setUpEditor()
      @setupUsers()
      @setupCalendar()

      if @model.isNew()
        @$('.js-toggle').trigger "click"
        $('#title').slugIt
          output: "#slug"

      # Fetch the tags and setup the selectize plugin.
      App.request "tag:entities", (tags) =>
        @setUpTags tags

    # Setup the markdown editor
    setUpEditor: ->
      # Custom toolbar items.
      toolbar = [
        'bold', 'italic', '|'
        'quote', 'unordered-list', 'ordered-list', '|'
        'link', 'image', 'code', '|'
        'undo', 'redo', '|', 'tags', 'calendar'
      ]

      @editor = new Editor
        toolbar: toolbar

      # Render to the #content holder.
      @editor.render(document.getElementById("content"))

      # Allow images to be drag and dropped into the editor.
      @imageUpload @editor

      # Set up the local storage saving when the editor changes.
      @editor.codemirror.on "change", (cm, change) =>
        @localStorage()

      # Manually over ride the editor status bar.
      @$('.editor-statusbar')
        .find('.lines').html(@editor.codemirror.lineCount())
        .find('.words').html(@editor.codemirror.getValue().length)
        .find('.cursorActivity').html(@editor.codemirror.getCursor().line + ':' + @editor.codemirror.getCursor().ch)

    # Save the post data to local storage
    localStorage: ->
      data =
        title: @$('#title').val()
        content: @editor.codemirror.getValue()
        tags: @$("#js-tags").val()

      # Save it manually so the first load has data.
      $.jStorage.set "post-#{@model.id}", data

      # Publish the data so any listeners can update.
      $.jStorage.publish "post-#{@model.id}", data

    # Populate the user select list.
    setupUsers: ->
      $userSelect = @$("#js-user")
      users = App.request "get:all:users"
      users.each (item) ->
        $userSelect.append $("<option></option>").val(item.id).html(item.get("first_name") + " " + item.get("last_name"))

      # If the model isNew then set the current user as author.
      if @model.isNew()
        user = App.request "get:current:user"
        $userSelect.val user.id
      else
        $userSelect.val @model.get("user_id")

    # Setup the tags as a selectize object.
    setUpTags: (tags) ->
      @$("#js-tags").selectize
        persist: true
        delimiter: ','
        maxItems: null
        options: @generateTagOptions(tags)
        render:
          item: (item) ->
            "<div><i class='icon-tag'></i> #{item.text}</div>"
          option: (item) ->
            "<div><i class='icon-tag'></i> #{item.text}</div>"
        create: (input) ->
          value: input
          text: input

    # Generate tags in a standard format for the plugin.
    generateTagOptions: (tags) ->
      opts = for tag in tags.pluck("tag") when tag isnt ""
        value: tag
        text: tag
      @customTags(opts)

    # Add any tags from the hidden input. Primarily used when using drag/drop.
    # This allows us to keep from going through the selectize api for adding and option and then the item.
    customTags: (opts) ->
      val = $("#js-tags").val()
      if val isnt ""
        for tag in val.split(",") when tag isnt ""
          opts.push
            value: tag
            text: tag
      opts

    # Toggle the tags based on toolbar click
    toggleTags: (e) ->
      if @tagsShown
        @$('.editor-toolbar a, .editor-toolbar i').show()
        @$(".tags-bar").hide();
      else
        @$('.editor-toolbar a, .editor-toolbar i').hide()
        @$('.icon-tags').show()
        @$(".tags-bar").show()
        @$("js-tags").focus()

      @tagsShown = !@tagsShown

    # Setup the date selection which is inside a qtip
    setupCalendar: ->
      @$(".icon-calendar").qtip
        show:
          event: "click"
        content:
          text: $("#date-form").html()
        position:
          at: "right center"
          my: "left center"
          viewport: $(window) # Keep the tooltip on-screen at all times
          effect: false
        events:
          render: (event, api) ->
            $(".js-date").each ->
              $(this).val $("#publish_date").val()
            $(".js-setdate").click (e) ->
              e.preventDefault()
              pubDate = $(e.currentTarget).parent().find('input').val()
              $("#publish_date").val pubDate
              $('.icon-calendar').qtip "hide"
        hide: "unfocus"

    # Save the post data
    save: (e) ->
      e.preventDefault()

      @processFormSubmit
        title: @$('#title').val()
        slug: @$('#slug').val()
        active: @$('input[type=radio]:checked').val()
        content: @editor.codemirror.getValue()
        tags: @$("#js-tags").val()
        user_id: @$("#js-user").val()
        publish_date: @$("#publish_date").val()

    # Process the form and sync to the server
    processFormSubmit: (data) ->
      @model.save data,
        collection: @collection

    # Show or hide the validation errors based on validation failure.
    changeErrors: (model, errors, options) ->
      if _.isEmpty(errors) then @removeErrors() else @addErrors errors

    # Loop through the errors and display
    addErrors: (errors = {}) ->
      @$("#js-errors").show().find("span").html(Lang.post_errors)
      for name, error of errors
        @addError error

    # Add any errors as a list item in the alert.
    addError: (error) ->
      sm = $("<li>").text(error)
      @$("#js-errors span").append sm

    # Remove all errors from the form.
    removeErrors: ->
      @$("#js-errors").hide()

    # Collapse the details fields
    collapse: (@$toggle) ->
      @$toggle.data("dir", "up").addClass("icon-chevron-sign-right").removeClass("icon-chevron-sign-down")
      @$(".details.hide").hide()

    # Expand the details fields
    expand: (@$toggle) ->
      @$toggle.data("dir", "down").addClass("icon-chevron-sign-down").removeClass("icon-chevron-sign-right")
      @$(".details.hide").show()

    # Toggle the post details
    toggleDetails: (e) ->
      @$toggle = $(e.currentTarget)
      if @$toggle.data("dir") is "up"
        @expand @$toggle
      else
        @collapse @$toggle

    # Toggle the save button text based on status
    changeBtn: (e) ->
      if e.currentTarget.value is "1"
        @$(".publish").text Lang.post_publish
      else
        @$(".publish").text Lang.post_save

    # Setup the image uploading into the content editor.
    imageUpload: (editor) ->
      options =
        uploadUrl: App.request("get:url:api") + "/dropzone/image"
        allowedTypes: ["image/jpeg", "image/png", "image/jpg", "image/gif"]
        progressText: "![Uploading file...]()"
        urlText: "![file]({filename})"
        onUploadedFile: (json) ->
          debugger
        errorText: "Error uploading file"
      # Attach it to the code mirror.
      inlineAttach.attachToCodeMirror(editor.codemirror, options)
