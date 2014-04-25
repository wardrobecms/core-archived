@Wardrobe.module "PostApp.List", (List, App, Backbone, Marionette, $, _) ->

  class List.PostItem extends App.Views.ItemView
    template: "post/list/templates/item"
    tagName: "tr"

    attributes: ->
      if @model.get("active") is "1" and @model.get("publish_date") > moment().format('YYYY-MM-DD HH:mm:ss')
        class: "post-item scheduled post-#{@model.id}"
      else if @model.get("active") is "1"
        class: "post-item active post-#{@model.id}"
      else
        class: "post-item draft post-#{@model.id}"

    triggers:
      "click .delete" : "post:delete:clicked"

    events:
      "click .details" : "edit"
      "click .preview" : "preview"

    onShow: ->
      allUsers = App.request "get:all:users"
      $avEl = @$(".avatar")
      if allUsers.length is 1
        $avEl.hide()
      else
        user = @model.get("user")
        $avEl.avatar user.email, $avEl.attr("width")
      @$('.js-format-date').formatDates()

    templateHelpers:
      status: ->
        if parseInt(@active) is 1 and @publish_date > moment().format('YYYY-MM-DD HH:mm:ss')
          Lang.post_scheduled
        else if parseInt(@active) is 1
          Lang.post_active
        else
          Lang.post_draft

    edit: (e) ->
      e.preventDefault()
      App.vent.trigger "post:item:clicked", @model

    preview: (e) ->
      e.preventDefault()
      storage = new Storage
        id: @model.id
      storage.put @model.toJSON()
      window.open("#{App.request("get:url:blog")}/post/preview/#{@model.id}",'_blank')

  class List.Empty extends App.Views.ItemView
    template: "post/list/templates/empty"
    tagName: "tr"

  class List.Posts extends App.Views.CompositeView
    template: "post/list/templates/grid"
    itemView: List.PostItem
    emptyView: List.Empty
    itemViewContainer: "tbody"

    events:
      "click .js-filter" : "filterPosts"
      "keyup #js-filter" : "search"

    onCompositeCollectionRendered: ->
      @doFilter "draft"

    showEmpty: (type) ->
      if not @$("td:visible").length
        quotes = [
          '"The scariest moment is always just before you start." ― Stephen King'
          '"There is nothing to writing. All you do is sit down at a typewriter and bleed." ― Ernest Hemingway'
          '"Start writing, no matter what. The water does not flow until the faucet is turned on." ―  Louis L\'Amour'
          '"All you have to do is write one true sentence. Write the truest sentence that you know." ― Ernest Hemingway'
          '"Being a writer is a very peculiar sort of a job: it\'s always you versus a blank sheet of paper (or a blank screen) and quite often the blank piece of paper wins." ― Neil Gaiman'
        ]
        @$(".js-quote").text quotes[_.random(quotes.length-1)]
        @$("table").addClass "hide"
        @$(".no-posts").removeClass("hide").find('span').text type

    hideAll: ->
      @$el.find(".post-item").hide()

    filterPosts: (e) ->
      e.preventDefault()
      @$("table").removeClass "hide"
      @$(".no-posts").addClass "hide"
      $item = $(e.currentTarget)
      type = $item.data "type"
      @$(".page-header").find(".active").removeClass("active")
      $item.addClass "active"
      @doFilter type

    doFilter: (type) ->
      @hideAll()
      @$("tr.#{type}").show()
      if @$("tr.#{type}").length is 0
        @showEmpty(type)

    search: (e) ->
      @handleFilter()

    handleFilter: ->
      @hideAll()
      filter = @$("#js-filter").val()
      return @$el.find(".post-item").show() if filter is ""
      @collection.filter (post) =>
        @isMatch(post, filter)

    isMatch: (post, filter) ->
      pattern = new RegExp(filter,"gi")
      foundId = pattern.test post.get("title")
      @$el.find(".post-#{post.id}").show() if foundId
