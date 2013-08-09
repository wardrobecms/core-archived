@Wardrobe.module "PostApp.Meta", (Meta, App, Backbone, Marionette, $, _) ->

  class Meta.Controller extends App.Controllers.Base

    initialize: (options = {}) ->
      metaItems = @buildCollection options.model
      view = @getView metaItems
      @show view

    buildCollection: (model) ->
      App.request("set:all:meta", [key: "", value: ""])

    getView: (items) ->
      new Meta.Grid
        collection: items
        parentId: @cid
