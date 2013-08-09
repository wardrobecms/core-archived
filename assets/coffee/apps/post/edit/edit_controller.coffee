@Wardrobe.module "PostApp.Edit", (Edit, App, Backbone, Marionette, $, _) ->

  class Edit.Controller extends App.Controllers.Base

    initialize: (options) ->
      { post, id } = options
      post or= App.request "post:entity", id

      @listenTo post, "updated", ->
        App.vent.trigger "post:updated", post

      App.execute "when:fetched", post, =>
        @layout = @getLayoutView post
        @listenTo @layout, "show", =>
          @showMeta()
        @show @layout

    getLayoutView: (post) ->
      new Edit.Post
        model: post

    showMeta: ->
      App.execute "show:meta", @layout.fieldsRegion, @post
