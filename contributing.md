# Contributing

Before contributing to this repository, please open up an issue or contact core contributors via email or gitter to discuss changes you want to make.

Please note we have a code of conduct, please follow it in all your interactions with the project.

## Pull Request Process

1. Open up a PR via GitHub and explain in depth what kind of changes you are making.
2. Include in the PR description the improvement's to the API if any.
3. Update the README.md in your PR with details of changes to the the API within your PR.
4. Please include in your PR if you believe your changes will be a breaking change.
5. PR's must be reviewed by a contributor before the PR will be merged in.
6. PR's must pass travis ci builds in order to be eligible for review.

## Code of Conduct


### Our Standards

Examples of behavior that contributes to creating a positive environment
include:

* Using welcoming and inclusive language
* Code must be read fluidly:
```php 
$book->chunk(3)->volume->search('search title') // BAD
$book->volume->search('search title')->chunk(3) // GOOD
```
* Gracefully accepting constructive criticism
* Focusing on what is best for the repoository
* Showing empathy towards other community members

### Attribution

This Code of Conduct is adapted from the [Contributor Covenant][homepage], version 1.4,
available at [http://contributor-covenant.org/version/1/4][version]

[homepage]: http://contributor-covenant.org
[version]: http://contributor-covenant.org/version/1/4/