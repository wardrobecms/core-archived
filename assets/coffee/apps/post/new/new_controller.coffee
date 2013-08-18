# New Post Controller
# -------------------
# Handles the actions for setting up the new post info.
@Wardrobe.module "PostApp.New", (New, App, Backbone, Marionette, $, _) ->

  class New.Controller extends App.Controllers.Base

    initialize: (options) ->
      # Request an empty post model
      post = App.request "new:post:entity"

      @storage = new Storage

      # Listen to the post created event and then send out a post:created event.
      @listenTo post, "created", =>
        @storage.destroy()
        App.vent.trigger "post:created", post

      # Get the post view.
      view = @getNewView post

      # Actually show and insert the view into the DOM.
      @show view

    # Get the new post view passing in an empty post model
    getNewView: (post) ->
      new New.Post
        model: post
        storage: @storage