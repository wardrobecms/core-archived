@Wardrobe.module "Views", (Views, App, Backbone, Marionette, $, _) ->

  class Views.ItemView extends Marionette.ItemView
    fillJSON: (data = {}) ->
      if @model?.isNew()
        @$('form').fillJSON(data)
      else
        @$('form').fillJSON(@model?.toJSON() or data)