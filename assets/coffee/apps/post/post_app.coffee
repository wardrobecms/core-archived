# Post App
# -------------------
# Handles the setup of managing all the posts
@Wardrobe.module "PostApp", (PostApp, App, Backbone, Marionette, $, _) ->

  class PostApp.Router extends Marionette.AppRouter
    appRoutes:
      "" : "add"
      "post" : "list"
      "post/add" : "add"
      "post/edit/:id" : "edit"

  API =
    # List all the posts in a grid.
    list: ->
      @setActive()
      new PostApp.List.Controller

    # Add a new post.
    add: ->
      @setActive ".write"
      new PostApp.New.Controller

    # Edit an existing post.
    edit: (id, item) ->
      @setActive()
      new PostApp.Edit.Controller
        id: id
        post: item

    # Set the top nav active based on which section you are in.
    setActive: (type = ".posts") ->
      $('ul.nav li').removeClass("active").find(type).parent().addClass("active")

  # Load the list of posts from an event.
  App.vent.on "post:load", ->
    App.navigate "post"
    API.list()

  # Listen for the post created or saved then show alert and redirect.
  App.vent.on "post:created post:updated", (item) ->
    $("#js-alert").showAlert(Lang.post_success, Lang.post_saved, "alert-success")
    App.navigate "post/edit/#{item.id}"
    API.edit item.id, item

  # When the new post link is clicked then show the add routine.
  App.vent.on "post:new:clicked post:new", ->
    App.navigate "/",
      trigger: false
    API.add()

  # When the edit post link is clicked then show the edit routine.
  App.vent.on "post:item:clicked", (item) ->
    App.navigate "post/edit/#{item.id}"
    API.edit item.id, item

  # Initialize the router.
  App.addInitializer ->
    new PostApp.Router
      controller: API
