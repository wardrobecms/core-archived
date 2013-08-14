@Wardrobe.module "AccountApp.New", (New, App, Backbone, Marionette, $, _) ->

  class New.User extends App.Views.ItemView
    template: "account/new/templates/form"
    className: "span12"

    events:
      "click .save" : "save"

    modelEvents:
      "change:_errors"  : "changeErrors"

    onRender: ->
      @fillJSON()

    save: (e) ->
      e.preventDefault()
      data =
        first_name: @$('#first_name').val()
        last_name: @$('#last_name').val()
        email: @$('#email').val()
        password: @$('#password').val()
        active: 1 # @$('input[type=radio]:checked').val()

      @model.save data,
        collection: @collection
