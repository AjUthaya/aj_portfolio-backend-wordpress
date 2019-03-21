# AJ Portfolio (Backend)

![logo_banner](https://imgur.com/f2beX1w.png)

This repository is a part of my personal portfolio project, it is built with the separation of the frontend and backend in mind (micro services). So that i have one backend, where all the data comes from and a frontend where the user sees the data presented in a pretty and user frinely way. This way if i feel like building a new website for my portfolio, all i have to build is a new frontend and reuse the data from the backend.

## Prerequisites

### System

Software that the system requires to be able to run this repository.

- [Docker](https://www.docker.com/products/docker-desktop) - Docker is a computer program that performs operating-system-level virtualization, also known as "containerization".

- [MySQL](https://www.mysql.com/) - MySQL is an open source relational database management system.

### Configuration

A list of the configuration files that you have to create, for every configuration file listed below there should a file with the suffix "\-example" before the extention. So all you have to do is copy that file and replace the values.

- wp-config.php

## Run

### Development

1. Coming soon

### Production

1. Coming soon

## Test

1. Coming soon

## Application

### Linter

Coming soon

### Architecture

![application_architecture](https://imgur.com/bxhhkqt.png)

### Design

Coming soon

## Versioning

We use git for versioning. For the versions available, see the [tags on this repository](https://github.com/AjUthaya/aj_portfolio-backend-wordpress/tags).

### Add a new version tag

1. Update the CHANGELOG file with a new section and the "Unreleased Changes" link with the new tag version

2. Create a new tag `git tag X.X.X`

3. Push the new tag to remote `git push origin --tags`

### Add a version tag for an older commit

1. Type in `git log` in the root of the repo, to list all the commits with ID's

- Press <kbd>Q</kbd> to get out of the list view

2. Create a tag for an older commit `git tag -a X.X.X COMMIT_ID`

3. Push the tag to remote `git push origin --tags`

### Remove a version tag

1. Remove tag `git tag -d X.X.X`

2. Remove tag from remote `git push -d origin X.X.X`

## Technologies

### [PHP](http://www.php.net/)

PHP stands for Hypertext Preprocessor (no, the acronym doesn't follow the name). It's an open source, server-side, scripting language used for the development of web applications.

### [WordPress](https://wordpress.com/)

WordPress is a free and open-source content management system based on PHP & MySQL. Features include a plugin architecture and a template system.
