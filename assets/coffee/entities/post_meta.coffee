@Wardrobe.module "Entities", (Entities, App, Backbone, Marionette, $, _) ->

  class Entities.PostMetaItem extends App.Entities.Model

  class Entities.PostMetaCollection extends App.Entities.Collection
    model: Entities.PostMetaItem

  API =
    setAll: (meta) ->
      new Entities.PostMetaCollection meta

  App.reqres.setHandler "set:all:meta", (meta) ->
    meta = [key: "", value: ""] if not meta
    API.setAll meta