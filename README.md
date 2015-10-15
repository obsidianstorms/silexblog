# basicblog

## Summary

This application is for examining available skills and capabilities on a start-to-finish project within a single iteration.

The specifications are as follows:

# Build Me A Blog

The goal of this project is to build a web blogging application which allows a single user to maintain a blog and other users to post comments on the available blog posts.

You will have one iteration to complete this project, please use this time wisely and put your best foot forward. Be sure to demonstrate proper understanding of the Software Development Life Cycle, Object Oriented Programming, RESTful Principals, and Test Driven Development. After this period your project will be subject to a code review and architectual analysis. 

Take your time, think through the task, and good luck!

# Technical Requirements

Terms found in this document such as MUST, SHALL, and MAY can be referenced from  [RFC 2119](https://www.ietf.org/rfc/rfc2119.txt).

The application MUST BE built using PHP 5.5+ with a mySQL 5.6 database running in an Apache 2 instance. You may use a system provisioning tool such as [Puphpet](https://puphpet.com) to generate your vagrant file and VM.

Your entire source code repository MUST BE available for review from our internal github instance.

You SHOULD set up your vagrant and puppet scripts so that any developer may stand up a complete and working instance of your application locally with the ````vagrant up```` command.

You MAY use any framework, templating engine, and/or tools you deem necessary to complete your task.

# Feature Requirements

* There SHOULD BE only one administrative user. This user can be created by a manual process.
* Other users MUST BE able to properly register for new accounts.
* All users MUST BE able to post comments on individual blog posts.
* The administrative user MUST BE able to create, update, and delete blog posts.
* The administrative user MUST BE able to delete any comment.
* There MUST exist at the root resource ("/") a chronological listing of all blog entries.
* There MUST exist a unique resource for each individual blog post which is linked to from the chronological listing.