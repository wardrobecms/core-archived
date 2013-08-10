@Wardrobe.module "PostApp.Meta", (Meta, App, Backbone, Marionette, $, _) ->

  class Meta.Controller extends App.Controllers.Base

    initialize: (options = {}) ->
      allMeta = App.request "meta:entities"
      metaItems = @buildCollection options.model
      view = @getView metaItems, allMeta
      @show view

    buildCollection: (model) ->
      App.request "set:all:meta", model

    getView: (items, allMeta) ->
      new Meta.Grid
        collection: items
        meta: allMeta
        parentId: @cid
