@Wardrobe.module "PostApp.Edit", (Edit, App, Backbone, Marionette, $, _) ->

  class Edit.Controller extends App.Controllers.Base

    initialize: (options) ->
      { post, id } = options
      post or= App.request "post:entity", id

      @storage = new Storage
        id: post.id

      @listenTo post, "updated", =>
        @storage.destroy()
        App.vent.trigger "post:updated", post

      App.execute "when:fetched", post, =>
        view = @getEditView post
        @show view

    getEditView: (post) ->
      new Edit.Post
        model: post
        storage: @storage