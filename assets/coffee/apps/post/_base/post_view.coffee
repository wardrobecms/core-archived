# Post View
# ---------------
# A parent view which the add and edit views extend from.
@Wardrobe.module "Views", (Views, App, Backbone, Marionette, $, _) ->

  class Views.PostView extends App.Views.ItemView
    template: "post/_base/templates/form"
    className: "span12"

    initialize: (opts) ->
      # Listen for when a markdown file is drag and dropped.
      App.vent.on "post:new:seed", (contents) =>
        @fillForm contents
      # Set a flag so we know when the tags are shown.
      @tagsShown = false
      @storage = opts.storage

    events:
      "click .publish" : "save"
      "click .js-toggle" : "toggleDetails"
      "click .icon-tags" : "toggleTags"
      "click .icon-user" : "showUsers"
      "click .icon-ellipsis-horizontal" : "insertReadMore"
      "click input[type=radio]" : "changeBtn"
      "keyup #title" : "localStorage"
      "change #js-user" : "localStorage"

    insertReadMore: ->
      if @editor.codemirror.getValue().match /!-- more/g
        @$("#js-errors").show().find("span").html Lang.post_more_added
      else
        @$(".icon-ellipsis-horizontal").addClass("disabled")
        @insert '<!-- more -->'

    # When the model changes it's private _errors method call the changeErrors method.
    modelEvents:
      "change:_errors"  : "changeErrors"

    templateHelpers:
      # Set the primary button text based on the model active status.
      submitBtnText: ->
        if @active? or @active is "1" then Lang.post_publish else Lang.post_save
      # Generate a preview url.
      previewUrl: ->
        id = if @id then @id else "new"
        "#{App.request("get:url:blog")}/post/preview/#{id}"

    # When the view is shown in the DOM setup all the plugins
    onShow: ->
      @setUpEditor()
      @setupUsers()
      @setupCalendar()
      @setupFilm()
      @localStorage()
      @_triggerActive()

      if @model.isNew()
        @$('.js-toggle').trigger "click"
        $('#title').slugIt
          output: "#slug"

      # Fetch the tags and setup the selectize plugin.
      App.request "tag:entities", (tags) =>
        @setUpTags tags

    _triggerActive: ->
      return @ if @model.isNew()
      if @model.get("active")
        @$(".js-active[value=1]").trigger("click")
      else
        $(".js-active[value=0]").trigger("click")

    # Setup the markdown editor
    setUpEditor: ->
      # Custom toolbar items.
      toolbar = [
        'bold', 'italic', '|'
        'quote', 'unordered-list', 'ordered-list', 'ellipsis-horizontal', '|'
        'link', 'image', 'code', 'film', '|'
        'undo', 'redo', '|'
        'tags', 'calendar'
      ]

      @editor = new Editor
        element: document.getElementById("content")
        toolbar: toolbar

      # Render to the #content holder.
      # @editor.render(document.getElementById("content"))

      # Allow images to be drag and dropped into the editor.
      @imageUpload @editor

      # Set up the local storage saving when the editor changes.
      @editor.codemirror.on "change", (cm, change) =>
        @localStorage()

      # Manually over ride the editor status bar.
      # @$('.editor-statusbar')
      #   .find('.lines').html(@editor.codemirror.lineCount())
      #   .find('.words').html(@editor.codemirror.getValue().length)
      #   .find('.cursorActivity').html(@editor.codemirror.getCursor().line + ':' + @editor.codemirror.getCursor().ch)

    # Save the post data to local storage
    localStorage: ->
      @storage.put
        title: @$('#title').val()
        slug: @$('#slug').val()
        active: @$('input[type=radio]:checked').val()
        content: @editor.codemirror.getValue()
        tags: @$("#js-tags").val()
        user_id: @$("#js-user").val()
        publish_date: @$("#publish_date").val()

    # Populate the user select list.
    setupUsers: ->
      $userSelect = @$("#js-user")
      users = App.request "get:all:users"
      users.each (item) ->
        $userSelect.append $("<option></option>").val(item.id).html(item.get("first_name") + " " + item.get("last_name"))

      # If the model isNew then set the current user as author.
      if @model.isNew()
        user = App.request "get:current:user"
        stored = @storage.get()
        if stored?.user_id then $userSelect.val stored.user_id else $userSelect.val user.id
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

    setupFilm: ->
      @$(".icon-film").qtip
        show:
          event: "click"
        content:
          text: $("#film-form").html()
        position:
          at: "right center"
          my: "left center"
          viewport: $(window) # Keep the tooltip on-screen at all times
          effect: false
        events:
          render: (event, api) =>
            $(".js-submitfilm").click (e) =>
              e.preventDefault()
              filmInput = $(e.currentTarget).parent().find('input')
              filmUrl = filmInput.val()
              @attachFilm filmUrl
              filmInput.val('')
              $('.icon-film').qtip "hide"

        hide: "unfocus"

    attachFilm: (filmUrl) ->
      if filmUrl.match /youtube.com/g
        @bulidYoutubeIframe filmUrl
      else if filmUrl.match /vimeo.com/g
        @buildVimeoIframe filmUrl
      else
        # I'd like to alert here
        return

    bulidYoutubeIframe: (filmUrl) ->
      filmUrl = filmUrl.replace /https?:\/\//, '//'
      filmUrl = filmUrl.replace /watch\?v=/, 'embed/'
      filmIframe = '<iframe width="560" height="315" src="' + filmUrl + '" frameborder="0" allowfullscreen></iframe>'
      @insert filmIframe

    buildVimeoIframe: (originalFilmUrl) ->
      filmUrl = originalFilmUrl.replace /https?:\/\/vimeo.com\//, '//player.vimeo.com/video/'
      filmIframe = '<iframe src="' + filmUrl + '?title=0&amp;byline=0&amp;portrait=0" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'
      @insert filmIframe

    insert: (string) ->
      @editor.codemirror.replaceSelection string

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
      @localStorage()
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
        # onUploadedFile: (json) ->
        errorText: "Error uploading file"

      # Attach it to the code mirror.
      inlineAttach.attachToCodeMirror(editor.codemirror, options)
