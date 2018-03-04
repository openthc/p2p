# Contributing

### Pull Requests

* Fill in [the required template](PULL_REQUEST_TEMPLATE.md)
* Do not include issue numbers in the PR title
* End all files with a newline

### Git Commit Messages

* Use the present tense ("Add feature" not "Added feature")
* Use the imperative mood ("Move cursor to..." not "Moves cursor to...")
* Limit the first line to 72 characters or less
* Reference issues and pull requests liberally after the first line
* When only changing documentation, include `[ci skip]` in the commit title

### Code Style Guide

* Prefer Tabs
* Try to follow PHP-FIG guidelines
* Line length should be "reasonable", wrapping at 72 is not required.
* Capitalize initialisms and acronyms in names, except for the first word, which should be lower-case:
  * `openDB` instead of `getDb`
  * `sqlQueryParse` instead of `SQLQueryParse`