@Wardrobe.module "PostApp.New", (New, App, Backbone, Marionette, $, _) ->

  class New.Controller extends App.Controllers.Base

    initialize: (options) ->
      @post = App.request "new:post:entity"

      @listenTo @post, "created", ->
        App.vent.trigger "post:created", post

      @layout = @getLayoutView @post

      @listenTo @layout, "show", =>
        @showMeta()

      @show @layout

    getLayoutView: (post) ->
      new New.Post
        model: post

    showMeta: ->
      App.execute "show:meta", @layout.fieldsRegion, @post
