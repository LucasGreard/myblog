# Myblog

This is my blog for project 5 (Formation : Openclassroom Dev PHP/Symfony).

For this project, I had to make a blog with the following features:

- the home page
- the page listing all the blog posts
- the page displaying a blog post
- the page for adding a blog post
- the page allowing to modify a blog post
- the pages allowing to modify / delete a blog post
- user login / registration pages

I had to develop an administration part which should be accessible only to registered and validated users.
The administration pages will therefore be accessible on conditions and I must ensure the security of the administration part.

On the home page, you must present the following information:

- your first and last name
- a photo and / or a logo;
- a catchphrase that looks like you (example: “Martin Durand, the developer you need!”);
- a menu allowing you to navigate among all the pages of your website;
- a contact form (upon submission of this form, an e-mail with all this information will be sent to you) with the following fields: - last name First Name - contact email - message - a link to your CV in PDF format
  and all the links to social networks where you can be followed (GitHub, LinkedIn, Twitter ...).

On the page listing all the blog posts (from the most recent to the oldest), you must display the following information for each blog post:

- the title
- the date of the last modification
- the châpo
- and a link to the blog post.

On the page presenting the details of a blog post, the following information must be displayed:

- the title
- the chapô
- the contents
- the author
- the date of the last update
- the form for adding a comment (submitted for validation)
- lists of validated and published comments

On the page for modifying a blog post, the user has the option of modifying the title, chapô, author and content fields.

In the footer menu, there should be a link to access the blog administration.

On the administration part, I have to make sure that only people with “administrator” right have access; other users will only be able to comment on articles (with validation before publication).

## Installation

Use PHP (version 7.3.5).
Use MySql (version 5.7.26)
Use Apache (version 2.4.39)
Use Wamp (like me)

```shell
$ git clone git@github.com:LucasGreard/myblog.git
```

Use Composer to install dependencies.

Import DataBase on your database center like PHPMyAdmin. (Available : diagrammes/bdd_projet_5.sql)

Configure dbConnect. (Available : models/Dbconnect.php --> \_\_construc())

Configure mail (Available : models/ContactManager.php --> sendMessage())

## Author

Created by Lucas Greard

Mentor : Adrien Tilliard
