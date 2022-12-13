<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/quarkmarino/periodic-tasks.test">
    <img src="logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">A full stack demo project periodic tasks creating module with Laravel & Livewire</h3>

  <p align="center">
    Tasks Test Demo Site
    <br />
    <a href="http://marianoescalera.me/login">View Demo</a>
  </p>
</p>

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Table of Contents</summary>
  <ol>
    <li><a href="#module-details">Module Details</a></li>
    <li><a href="#task-examples">Task Examples</a></li>
    <li><a href="#iteration-types">Iteration Types</a></li>
    <li><a href="#notification-types">Iteration Types</a></li>
    <li><a href="#stubbing-and-logging">Stubbing & Logging</a></li>
    <li><a href="#users">Users</a></li>
    <li><a href="#notifications-form">Notifications Form</a></li>
    <li><a href="#evaluations">Evaluations</a></li>
    <li><a href="#instalation">Installation</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

# Module Details
It is required to develop a module that allows any user to create periodic tasks
The CRM displays the tasks as a LIST of tasks (not in graphical calendar format)
A pending task is considered "completed" when marked as done.

## Task Examples
- Every day
- Every Monday
- Every Monday
- Wednesday and Friday, every 5th of each month
- Every 5th of March of each year

## Iteration Types
- From date A to date B.
- For N iterations

## Grouping of Tasks
The tasks as a LIST of tasks must group them by
- TASKS TODAY
- TASKS TOMORROW
- TASKS NEXT WEEK
- TASKS NEXT MONTH
- TASKS NEXT

## Conceptual Description

### Structure the process model

There are several concepts to consider about the diversity of the types of periodicity for the tasks

    - Iteration Types
        - Bounded (`Starts at`, `Ends At`)
            - First periodc task would be `Starts at` if schedule conditions apply
            - Last periodic task would by `Ends At` if schedule conditions apply
        - Bounded Limited (`Starts at`, `Ends At`) by `Times`
            - First periodc task would be `Starts at` if schedule conditions apply
            - Number of scheduled dates will be limited by the number `Times`
            - Last periodic task would by `Ends At` if schedule conditions apply unless `Times` limit applies
        - Partially Bounded Limited (`Starts at`, `null`) by `Times`
            - First periodc task would be `Starts at` if schedule conditions apply
            - Number of scheduled dates will be limited by the number `Times`
    - `Times` (`null`, `integer`) limit
        - It counts for the exact number of days with in the scheduled "Task" periodic events for a single day.
        - Default `null` i.e. `Ends At` will be required when `Times` is `null`
    - `Every` (integer) spacer
        - It makes the `Time Scale` to space every `Every` times
        - Default 1, can't be `null`
        - Applies to any `Time Scale`
        - E.g.
            - Every [1, \*] day
            - Every [1, \*] week
            - Every [1, \*] month

The following `Time Scales` where considered (App\Data\Enums\TimeScaleEnum) for creating schedules

    - Day Scale
        - Simplest
        - Iterate over the Task period
    - Week Scale
        - I iterates over each `Week`
        - `Week` starts on Sunday (Carbon::SUNDAY)
        - `Week` ends on Saturday (Carbon::SATURDAY)
        - Parameters
            - `Week Days`
                - Defines the valid week days
                - One or More (Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday)
        - Allows for periodicities such as:
            - Every Monday
            - Every Monday, Wednesday and Friday
            - Every Monday, Tuesday and Saturday
            - Every Sunday, Monday, Tuesday, Wednesday, Thursday, Friday and Saturday
            - Every Tuesday and Thursday
            - Etc.
    - Month Scale
        - I iterates over each `Month Scale`
            - Any of (January, Febratu, March, April, May, June, July, August, September, October, November, December)
        - Starts on `Month Scale` first day, e.g. 04/01/2021
        - Ends on `Month Scale`'s last day
            - Considers months with less that 31 days appropriately
            - E.g.
                - 02/28/2021
                - 04/30/2021
                - 10/31/2021
                - Etc.
        - Parameters
            - `Month Day`
                - Indicates the day of the month.
                - Any of [01-(28,29,30,31)] respectively of the `Month`
                - Required when an `Nth Week Day` is not defined
            - `Nth Week Day`
                - Indicates the nth week day of the month.
                - Parameters
                    - `Nth`, any of (1st, 2nd, 3rd, 4rd, 5t)
                    - `WeekDay` one of (Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday)
                - One of the combinations of (`Nth`) x (`WeekDay`) respectively of the `Month`
            - `Month`
                - Indicates the month over any of [`Month Day`, `Nth Week Day`](exclusive)
                - If not defined, all months are cosidered

So, a periodic date would be dfined as follows

    - An `Starts At` date, (default to today)
    - One of
        - `Ends At` (end of the curent year for the preview dates)
        - `Times` (nullable, default null)
    - An `Every` value (default 1)
    - A `Time Scale`, any of
        - `Day Scale`
        - `Week Scale`
            - At least one `Week Days` day
        - `Month Scale`
            - `Month` (nullable for all months)
            - One of
                - `Month Day` (required if no `Nth Week Day`)
                - `Nth Week Day` (required if no `Month day`)
                    - An `Nth` (required)
                    - A `WeekDay` (required)
    - A `Task Description`

### Structure the data

To minimize and normalize the DB stored data, the following architecture is defined
    - Task
        - Has One
            - WeekSchedule
            - MonthSchedule
        - Has Many
            -Completions

When a `Task` is defined, it only stores the `Starts At`, `Ends At`, `Every`, `TimeScale`, `Times` and `Task Description` on itself
It maintains its `WeekScale` and `MonthScale` configurations on `WeekSchedule` and `MonthSchedule` respectively

The `WeekSchedule` defines the single
    - `Week Days` (stored as JSON)

The `WeekSchedule` defines the
    - `Month Day` (nullable)
    - `Nth Week Day` (nullable, stored as JSON)
    - `Month` (nullable)

The `Completions` defines the
    - `Task Date` Actual Task date
    - `Completed At` The date of completion

### Opinions and hypothetical observations
Handling dates are no easy task by far, so many cumbersome and caveats have to be considered and date manipulation can sometimes be quirk.

#### Design Process
Initial Considerations (as a though process reference)
- Repeating Time Scales
    - Times
        - Starts on `start_at`
        - runs for the `times` number of times

    All task dates start from `starts_at` and end before or at `ends_at`, and runs for the `times` number of times or unlimited if `times` is null

    - Day
        - Repeats every `every` number of days e.g.
            - Day (1), runs every single day
            - Day (2), runs every other day
            - Day (3), runs every third day
    - Week
        - Repeats every week at the selected week_days, any of (sun, mon, tue, wen, thu, fri, sat)
    - Month
        - Repeats every nth day of the month, 01-31, (Jan, Feb, Mar, Apr, May, Jun, Jul, Ago, Sep, Oct, Nov, Dec)
        - Repeats every nth week day of the month cross operation between
            Nth day, (first, second, third, fourth, fifth)
            Week day, (sun, mon, tue, wen, thu, fri, sat)
    - Year
        - Repeats every nth day of the year, 01-[365,366]

Although couldn't cover all the initially described situations given the time frame for the test, I'd be cool to have a much more flexible week and month scheduling, such as:

- Multiple alternating/rotating week/month configurations
- Date exceptions:
    - Date skipping
    - Date offsetting

# More Information

## Describe
The functionality or development that you have most "enjoyed" programming/solving throughout your professional career.
- There have been several functionalities I've enjoyed so much while developing
    - Refactoring for a D.0 (insurance charging) oneline standard
        - To use Design Patterns such as:
            - Builder
            - Factory
            - Visitor
                - This one was the most fun and interesting, it allowed the structure to parse from the D.0 format into a traversavble, convertable tree structure and to convert to the online back easily, it allowed to preconfigure test data and run them in a much more manageable/maintainable way
    - Implementing a very handy tool to generate the HTML layout for PDF printing in a very fluent way, very similar to laravel collections “High Order Messages”.
        - It allowed to create the document structure programatically
            - document >
                - header <
                - body >
                    - section1 >
                        - content >
                            - table <<<
                    - section2 >
                        - content >
                            - list <<<<
                - footer
        - Then that structure can be provided to a single blade view file that will render any configuration leveraging blade components
    - The third one would be the migration of a page from CodeIgniter2 into Laravel 8
        - This migration allowed me to completelly reconsider the usability and need of controllers
        - I discarded them completely (proudly), in favor of blade view components
        - That allowed me to isolated responsability very granularlly
            - And avoid funneling many intentions through a sinlge action and then reverse engineering it to split the data to the respective parts of the page.
        - It was much more like, asking to render a page and each piece of it knowing exactly what/how to do it, very self organizing, independent and reusable
            - A really joy to put everithing together.
## Answer the following questions:
- What is docker?
    - It is a containerization service that allow to ship computing resoures in a very modular, maintainable and consistent way
    - I do use it extensively for devlopment, via Laradock or laravel sail
- What is serverless?
    - Is a method of providing computing resources in a much more scalable and transparent way, with minimal or zero configuration and maintanance from the developers over the actuall physical servers
- What is git? What is a merge request?
    - It is a Source code version controll
        - It relies on the concept of distributed repository
        - It allows to create branches for maintaining main code base and features and experiments independent.
            - Branches allows for fast task switching and organization
            - Branches can be splitted and merged
    - A merge request would be the process of asking to the git repository owner to consider a branch to be merged into any other branch (mainly to develop branch) for formally aggregating the changes of a developer to the repository
        - it would require a code review process and running and passing proper tests for it to be considered to be merged

### Installation

1. Clone the repo
    ```sh
    git clone https://github.com/quarkmarino/periodic-tasks.test
    ```
2. Install Composer dependencies
    ```sh
    cd periodic-tasks.test
    composer install
    ```
3. Fire Up Sail (requires Docker)
    ```sh
    sail up -d
    ```
4. Run artisan migrations
    ```sh
    sail art migrate
    ```
5. Compile Scripts and Styles
    ```sh
    sail yarn run dev
    ```
6. Visit http://periodic-tasks.test
    6.1 login with the following credentials
      user: admin@periodic-tasks.test
      password: password
    6.2 visit http://periodic-tasks.test/tasks
        6.2.1 Design a task via the top-left form
        6.2.2 Inspect the preview of the dates
        6.2.3 Add the task
        6.2.4 Inspect the tasks list, filter the tasks and mark tasks as completed in case

<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- CONTACT -->
## Contact

Jose Mariano Escalera Sierra - [@quarkmarino](https://twitter.com/quarkmarino) - mariano.pualiu@gmail.com

Project Link: [https://github.com/quarkmarino/periodic-tasks.test](https://github.com/quarkmarino/periodic-tasks.test)

