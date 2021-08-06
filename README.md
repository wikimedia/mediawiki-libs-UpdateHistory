[![Latest Stable Version]](https://packagist.org/packages/wikimedia/update-history) [![License]](https://packagist.org/packages/wikimedia/update-history)

wikimedia/update-history
=====================

A simple tool to update HISTORY.md files when making a library release.

Additional documentation about this library can be found on
[mediawiki.org](https://www.mediawiki.org/wiki/UpdateHistory).

Usage
-----

To make a release:

## Step 1
```bash
bin/update-history [patch|minor|major]
```

This increments the version number for a patch release (or, if you
specify, for a minor or major release instead) and updates the
HISTORY.md with the new version number and the current date.

## Step 2
```bash
git add HISTORY.md
git commit -m "Release <My Package> <VERSION>"
git tag <VERSION>
```
This step will be automated in the future.

## Step 3
```bash
bin/update-history
```
This adds a new placeholder "x.x.x (not yet released)" section to
the HISTORY.md.

## Step 4
```bash
git add HISTORY.md
git commit -m "Bump HISTORY.md after release"
```
This step will be automated in the future.

## Step 5 (optional)
Push these commits to your code review system.
```bash
git review
```
When they are merged you may have to verify that the tag created above
still corresponds to the final merged commit, and if not:
```bash
git tag -f <new git hash> <VERSION>
```
And finally, push the new tag:
```bash
git push origin <VERSION>
```

---
[Latest Stable Version]: https://poser.pugx.org/wikimedia/update-history/v/stable.svg
[License]: https://poser.pugx.org/wikimedia/update-history/license.svg
