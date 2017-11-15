Development Guide and How to Contribute
=======================================

Contributing
------------

If you want to contribute to Proethos2 and make it better, your help is very welcome. Contributing is also a great way to learn more about social coding on Github, new technologies and and their ecosystems and how to make constructive, helpful bug reports, feature requests and the noblest of all contributions: a good, clean pull request.

### How to make a clean pull request

Look for a project's contribution instructions. If there are any, follow them.

- Create a personal fork of Proethos2 on Github.
- Clone the fork on your local machine. Your remote repo on Github is called `origin`.
- Add the original repository as a remote called `upstream`.
- If you created your fork a while ago be sure to pull upstream changes into your local repository.
- Create a new branch to work on! Branch from `develop` if it exists, else from `master`.
- Implement/fix your feature, comment your code.
- Follow the code style of Proethos2, including indentation.
- Run the tests!
- Write or adapt tests as needed.
- Add or change the documentation as needed.
- Push your branch to your fork on Github, the remote `origin`.
- From your fork open a pull request in the correct branch. Target Proethos2's `develop` branch if there is one, else go for `master`!
- If the maintainer requests further changes just push them to your branch. The PR will be updated automatically.
- Once the pull request is approved and merged you can pull the changes from `upstream` to your local repo and delete
your extra branch(es).

And last but not least: Always write your commit messages in the present tense. Your commit message should describe what the commit, when applied, does to the code – not what you did to the code.

Development Guide
-----------------

This links will help you if you want to be a developer from Proethos2 project, or help as you can, not necessarily as developer.
If you have any questions, please [open an ticket here](https://github.com/bireme/proethos2/issues).

- [Instructions to use Make shortcuts](make-shortcuts.md)
- [Relations between Proethos2 and XML ICTRP](relations-between-proethos2-and-XML-ICTRP.md)
- [Translation Guide](translation-guide.md)
- [How to migrate from Proethos1](how-to/how-to-migrate-from-proethos1.md)
- [How to generate help messages in database to my new development](how-to/how-to-generate-help-messages-in-database-to-my-new-development.md)
- [How to generate new database initial data from my dev environment](how-to/how-to-generate-new-database-initial-data-from-my-dev-environment.md)
- [How to configure reCaptcha for registration form](how-to/how-to-configure-recaptcha-for-registration-form.md)
- [How to add routines in crontab](how-to/how-to-add-routines-in-crontab.md)
- [How to create custom fields](how-to/how-to-create-custom-fields.md)
- [How to change the Submission Flow](how-to/how-to-change-the-submission-flow.md)
