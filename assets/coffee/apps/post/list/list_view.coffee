@Wardrobe.module "PostApp.List", (List, App, Backbone, Marionette, $, _) ->

  class List.PostItem extends App.Views.ItemView
    template: "post/list/templates/item"
    tagName: "tr"

    attributes: ->
      if @model.get("active") is "1"
        class: "post-item post-#{@model.id}"
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
    className: "span12"

    events:
      "keyup #js-filter" : "filter"
      "change #js-sort" : "sort"

    hideAll: ->
      @$el.find(".post-item").hide()

    filter: (e) ->
      @handleFilter()

    sort: (e) ->
      @handleFilter()

    handleFilter: ->
      @hideAll()
      sorter = @$("#js-sort").val()
      filter = @$("#js-filter").val()
      return @$el.find(".post-item").show() if sorter is "" and filter is ""
      @collection.filter (post) =>
        @isMatch(post, sorter, filter)

    isMatch: (post, sorter, filter) ->
      foundId = if sorter is "" or post.get("active").toString() is sorter then post.id else null
      if foundId and filter isnt ""
        pattern = new RegExp(filter,"gi")
        foundId = pattern.test post.get("title")
      @$el.find(".post-#{post.id}").show() if foundId
