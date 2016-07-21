How to generate help messages in database to my new development
===============================================================

If you are creating a new section, screen or anything that need a help button, you need to follow these steps:
- Create the button link with this code: `<a href="#" data-toggle="modal" data-target="#modal-help"><i class='glyphicon glyphicon-question-sign'></i></a>`
- Go to `symphony/` directory and run this code to generate a database register and associate:
```
php app/console proethos2:generate-help-messages
```
- Update database initial data
- Commit