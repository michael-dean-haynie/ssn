## Exercise Description

> "Simple Social Network"
> 
> A social network has n active users, identified by a name.  Users can be selectively friended to other users, allowing a network of shared content that any user can view, based on some of the rules and definitions below:
> 
> Users can be friends. Two users, x and y, are DIRECT friends if they friend each other on the network.
> 
> Users can be indirect friends. Two users, x and z, are INDIRECT friends if there exists some direct friend, y, common to both users x and z.
> 
> Users can also post content, which is visible to other users if and only if they are direct or indirect friends.
> 
> For the purpose of this exercise, the website does not need any visual/UI element. It only needs to be interactable via RESTful APIs, supporitng the following behaviors:
> 
> 1. Add a new user.  Must supply a name for the user. Duplicate names cannot be allowed.
> 
> 2. Connect two users as friends. Must supply the name for each user. 
> 
> 3. Check if content is viewable.  Must supply two users. The response will say if the users can view eachother's content based on the rules above.
> 
> As a followup, please describe how you would approach adding the following features and enhancements to the above website, once it was built:
> 
> 1. User names are no longer unique. All users are instead identified by some unique id, and names can be duplicated as much or little as one likes.
> 
> 2. The site administrators would like access to some kind of report that shows them how large the network is for each user.

## Installation / Setup
I chose to use Laravel (v5.5.27) just becaues I'm most familiar with it at the moment.

### Requirements
1. PHP >= 7.0.0
2. A local MySQL server instance

### Application Setup

1. Clone this repo: https://github.com/michael-dean-haynie/ssn.git
2. Check out _master_
3. From the project root, in terminal/cmd run "composer install" (alternatively you can checkout the "no-composer" branch that includes all the dependencies allowing you to skip this step.)
4. Save /.env.example as /.env
5. Initialize DB and DB user for the application by running  /database/create\_db\_and\_user.sql
6. From the project root, use laravel's command line tool 'artisan' to run the schema migrations "php artisan migrate"
7. From the project root, serve the application by running "php artisan serve"
8. In a browser navigate to http://localhost:8000 to verify that the application is indeed being served.

## API Description

#### POST /api/user
* Creates a user
* Requires 1 request-body parameter 'name' and the value should be the name of the user being created.


#### POST /api/friendship
* Creates a friendship between 2 users
* Requires 2 request-body parameters ('user\_a' and 'user\_b') who's values are the names of the users the friendship is being created for.

#### GET /api/friendship/check
* Checks if 2 users are direct or indirect friends. Returns true or false.
* Requires 2 query-string parameters ('user\_a' and 'user\_b') who's values are the names of the users that are being checked.

* ex: /api/friendship/check?user\_a=John&user\_b=Jane


## Follow Up Questions
> 1. User names are no longer unique. All users are instead identified by some unique id, and names can be duplicated as much or little as one likes.

This was actually my first instinct and I found myself fighting the urge use an underlying unique identifier throughout the exercise. It wasn't that difficult, just had a ton of red flags.

Transitioning toward a schema design where users' names were not required to be unique wouldn't be a huge deal in such a small scope project. But as the complexity grew (and the database grew) it would increasingly troublesome to make these changes existing data. My approach would be something like this:

Schema Changes / Data Changes

1. Add a unique id column to the users table
2. Assign existing users unique id's
3. Update the foreign keys of the other tables that have been affected

Application Logic Changes

1. The API endpoints would mostly see a swap out of name based parameters to id based parameters
2. The Business logic behind the API would also require a significant amount of refactoring / testing to user id's instead of names.

It's almost always a good idea to have a unique id column that has nothing to do with the actual data in the rest of the columns. Even on association tables. And always better to do this _before_ there's any live data in those tables.



> 2. The site administrators would like access to some kind of report that shows them how large the network is for each user.

That wouldn't be very difficult at all. As it is, the application is doing more 'sql work' in php than I would like. But it's a trade off that favors code simplicity over performance.

The methods on the user model (/app/User.php) would make it quite simple (code-wise) to query all the users and then generate a report of their network sizes' for both direct/indirect users.

Long term solution, I would definately put that logic into a sproc. That would get rid of almost all of the network overhead (just one request) and sql is just better at that relational magic than php. That's what it's _for_.

Something my current boss likes to say is 'premature optimization is the root of all evil'. I can always go back and write that sproc to generate the report. It's not half as messy as the unique id changes from the first question. That _is_ something you want to get right the first time.