# The Roadmap — From Zero to a Working App

*Laravel MVC Reference Cards · Build order, not the card order · One working app*

The cards are filed by MVC concept. A project is built the other way — **feature by feature**, and inside each feature always **data first, then the controller method, then the view**. This roadmap is that path, split into small steps you can tick off one at a time.

Card codes (e.g. `RT4`, `CT7`) refer to the reference cards at [edu.deblauwe.be](https://edu.deblauwe.be/roadmap.html). The roadmap says *what next*; the card says *how*.

## How to Use This Page

- **Tick a box only when its _Test_ passes** — not when you typed it. If you can't test it yet, you skipped a step.
- **Do the steps in order inside a feature.** You can reorder whole features, but never build a View before the Controller method that feeds it exists.
- **Commit after (almost) every ticked box.** The brief grades commit history — per feature *and* daily. A green checklist with three commits scores worse than a messy one with forty.

## The Golden Rhythm

For **every** piece of a feature, build in this exact order, one method at a time:

1. **Model / data** — migration, model class, relationship, factory, seed. Nothing renders until the data exists. → `MB1`, `MB3`, `MB4`
2. **Route + Controller method** — add the one route, write the one method it points at. → `RT1`, `CT1`, `CT7`
3. **View** — the Blade file that method returns. Presentation only. → `VW1`
4. **Test it** — hit the URL / submit the form, confirm it does the one thing, *then* tick.

Every controller method is a subset of the same five steps: **Authorize → Load data → Validate → Touch the model → Respond.** Keep `CT7` open; it is the map for steps 2–4.

---

## Phase 0 — Plan & Scaffold

Do this before writing a single feature. Half the mistakes are naming mistakes decided in this phase. → `WB5`, Global conventions

### Refresh the three ideas everything rests on

- [ ] Re-read what MVC actually splits, and why the split matters. → `WB1`, `WB4`
  - *Test:* for any page you plan, you can say what its Model, Controller and View each do.
- [ ] Re-read the request-response cycle — every step is one request in, one response out. → `WB2`
  - *Test:* you can trace click → route → controller → view → response in your head.

### Pick a theme and map the data first

- [ ] Pick a theme that naturally has news, a FAQ and user accounts with an admin (club, event, local shop).
  - *Test:* you can name, in one sentence each, what News / Profile / FAQ / Contact mean for your theme.
- [ ] Sketch every entity and its columns on paper before touching code. → `MB1`
  - *Test:* the sketch matches the table below (rename nouns to your theme, keep the shapes).
- [ ] Decide which page belongs to which audience: public / logged-in user / admin. → `WB3`
  - *Test:* you can say for each page which of the three can reach it.

**The data you are building** (adapt the names, keep the relationships):

| Entity | Key columns | Relationships |
| --- | --- | --- |
| `User` (built-in) | + `is_admin`, `username`, `birthday`, `avatar`, `bio` | hasMany `news` |
| `News` | `title`, `image`, `content`, `published_at`, `user_id` | belongsTo `author` (User); belongsToMany `tags` |
| `Tag` *(optional)* | `name` | belongsToMany `news` (pivot `news_tag`) |
| `Category` | `name` | hasMany `questions` |
| `Question` | `question`, `answer`, `category_id` | belongsTo `category` |
| `ContactMessage` *(optional)* | `name`, `email`, `body` | – |

This gives the required **one-to-many** (User → News, Category → Question), plus an *optional* **many-to-many** (News ↔ Tag, Phase 6) for extra credit. Admin is *not* a relationship — it is a single `is_admin` boolean on the user. → `MR1`, `MR3`, `MR4`

### Get an empty Laravel 13 running

- [ ] Create the project, `git init`, first commit ("INIT: fresh Laravel 13"). Commit early, commit often.
  - *Test:* `git log` shows one commit; the repo is on GitHub.
- [ ] Point `.env` at your database, then run `php artisan migrate`. → `WB6`
  - *Test:* the default `users`, `cache`, `jobs` tables exist in your DB client.
- [ ] Confirm `php artisan migrate:fresh --seed` runs clean — the teacher runs exactly this. → `WB6`
  - *Test:* command finishes with no red error, DB is rebuilt.

---

## Phase 1 — The Two Layouts (the Frame)

The brief demands **at least two layouts**. Build them now so every page you add later just drops into a frame. Layouts and form inputs are the same "view component" tool. → `VW2`, `VW5`

- [ ] Make the **public** layout component (header, nav, footer, `{{ $slot }}`). → `VW2`
  - *Test:* a throwaway page wrapped in `<x-site-layout>` shows the nav and footer.
- [ ] Make the **admin** layout component (a distinct frame — sidebar / admin nav). → `VW2`
  - *Test:* an admin page renders in a visibly different frame from the public one.
- [ ] Add a `title` prop with a default so pages can set their own `<title>`. → `VW2`
  - *Test:* `<x-site-layout title="Home">` changes the browser tab text.
- [ ] Home page: route `/` → a controller method → a view using the public layout. → `RT1`, `CT1`, `VW1`
  - *Test:* visiting `/` shows your homepage inside the public layout.

---

## Phase 2 — Authentication (Log In / Out / Register / Reset)

The brief wants the **standard** auth set: login, logout, remember-me, register, and forgot-password reset. Don't hand-roll these — install a starter kit (e.g. Laravel Breeze), then read the auth cards to understand what it gave you. → `RT5`, `VW4`

- [ ] Install the auth starter kit and run its migrations. → `WB6`
  - *Test:* `/register` and `/login` pages exist and render.
- [ ] Register a new account, then log out. → `VW4`
  - *Test:* a new row appears in `users`; logout returns you to a guest state.
- [ ] Log in with "remember me" ticked. → `RT5`
  - *Test:* close the browser, reopen — you are still logged in.
- [ ] Walk the forgot-password flow (dev mail can be the `log` driver).
  - *Test:* the reset link appears in `storage/logs/laravel.log` and a new password works.
- [ ] Protect one test route with the `auth` middleware. → `RT5`
  - *Test:* logged out you are bounced to `/login`; logged in you get through.

---

## Phase 3 — The Admin Flag & the Admin Gate

A user is a normal user **or** an admin. Keep it simple: a single `is_admin` boolean on the built-in User model — no separate Role table. → `WB3`

### Data

- [ ] Add a migration that puts an `is_admin` boolean column on the `users` table, `->default(false)`. → `MB3`, `WB6`
  - *Test:* `migrate:fresh` gives every user an `is_admin` column that starts false.
- [ ] Make `is_admin` mass-assignable and cast it to `boolean` on the User model. → `MB4`
  - *Test:* Tinker: `User::first()->is_admin` is a real `true`/`false`, not the string `"0"`.

### Seed the default admin

- [ ] Seed the required default admin with `is_admin` set to `true`. → `MB2`
  - Username `admin`, email `admin@ehb.be`, password `Password!321`.
  - *Test:* logging in as `admin@ehb.be` / `Password!321` works, and their `is_admin` is true.

### The admin gate

- [ ] Create the admin middleware with `php artisan make:middleware EnsureUserIsAdmin`; abort 403 unless the logged-in user's `is_admin` is true. → `RT6`
  - *Test:* the middleware checks the logged-in user's `is_admin` flag.
- [ ] Register it as the alias `admin`. → `RT6`
  - *Test:* `->middleware('admin')` is accepted (no "middleware not found").
- [ ] Add an admin route group: `prefix('admin')`, `name('admin.')`, `middleware(['auth','admin'])`. → `RT3`, `RT5`, `RT6`
  - *Test:* the group exists in `route:list` under the `admin.` name prefix.
- [ ] Admin dashboard: one route in the group → `Admin\DashboardController@index` → a view in the admin layout. → `CT1`, `CT2`, `VW1`
  - *Test:* a normal user gets 403; the admin sees the dashboard.

---

## Phase 4 — User Management (Admin Only)

Admins can create users manually and promote/demote admins. One method at a time, all inside `Admin\UserController`. → `RT4`, `CT7`

- [ ] `Route::resource('users', ...)` inside the admin group; `Admin\UserController`. → `RT3`, `RT4`
  - *Test:* `route:list` shows `admin.users.*` routes.
- [ ] **index** — list all users, flagging which ones are admins. → `CT2`, `VW1`
  - *Test:* `/admin/users` lists every seeded user and shows who is admin.
- [ ] **create + store** — form to add a user, with an "is admin?" checkbox; validate and save (the checkbox sets `is_admin`). → `CT4`, `VW3`, `VW4`
  - *Test:* submitting the form creates a user; ticking "admin" sets their `is_admin` to true.
- [ ] **promote / demote** — flip a user's `is_admin` (an update or a small dedicated action). → `CT5`
  - *Test:* toggling a user flips their `is_admin`; you cannot demote yourself into lockout.
- [ ] Confirm every user-management route sits behind `auth` + `admin`. → `RT6`
  - *Test:* a normal user hitting any `/admin/users` URL gets 403.

---

## Phase 5 — Profile Pages (Public View + Edit Your Own)

Every user has a **public** profile anyone can see, and can edit **their own** data. Profile fields live on the `users` table (or split into a one-to-one `Profile` to practise that relationship). → `MR2`

### Data

- [ ] Migration to add `username`, `birthday`, `avatar`, `bio` to `users` (all nullable). → `MB3`
  - *Test:* `migrate:fresh` adds the columns; existing seed still runs.
- [ ] Make the new fields mass-assignable (`$fillable` / `$guarded`). → `MB4`
  - *Test:* Tinker: `$u->update(['bio' => 'hi'])` saves without a mass-assignment error.

### Public profile (everyone, no login)

- [ ] Route `/users/{user}` → `ProfileController@show` (route-model binding). → `RT2`, `CT3`
  - *Test:* logged **out**, visiting another user's URL shows their public profile.
- [ ] Show view: username, birthday, avatar, bio. Escape all output with `{{ }}`. → `VW1`
  - *Test:* a bio containing `<script>` renders as text, not code (XSS protection).

### Edit your own (logged-in only)

- [ ] Route behind `auth`: `edit` → `update`, pre-filled with the current user's data. → `RT5`, `CT5`, `VW4`
  - *Test:* the edit form loads with your existing values in the fields.
- [ ] Authorize: a user may edit **only their own** profile. → `CT7`
  - *Test:* trying to edit someone else's profile is blocked (403 / redirect).
- [ ] Handle the avatar upload and store it on the server; save the path. → `VW3`
  - *Test:* uploading an image saves the file under `storage/app/public` and the profile shows it.
  - *Test:* run `php artisan storage:link` once so uploaded images are reachable.

---

## Phase 6 — News (the Flagship One-to-Many CRUD)

Public visitors read the news; admins manage it. `News belongsTo User (author)` is your **one-to-many**. Build the public side first (it's smaller), then the admin CRUD one method at a time. An *optional* `News belongsToMany Tag` at the end adds a **many-to-many** for extra credit. → `MR3`, `MR4`, `RT4`

### Data

- [ ] Create model, migration and factory: `php artisan make:model News -mf`. → `WB6`, `MB1`
  - *Test:* the model, migration and factory files all exist.
- [ ] Migration: `title`, `image`, `content` (text), `published_at`, and `user_id` foreign key. → `MB3`
  - *Test:* `migrate:fresh` builds the `news` table with the FK to `users`.
- [ ] Relationships: `author()` (belongsTo) on News, `news()` (hasMany) on User. → `MR3`, `MB4`
  - *Test:* Tinker: `News::first()->author` returns a User; `User::first()->news` a collection.
- [ ] Factory + seed a batch of news, each linked to a real user. → `MB2`
  - *Test:* after `migrate:fresh --seed`, the `news` table has rows with valid `user_id`s.

### Public: list & detail (only 2 of the 7 routes)

- [ ] Public route `->only(['index','show'])` → `NewsController`. → `RT4`
  - *Test:* `route:list` shows only `news.index` and `news.show` publicly.
- [ ] **index** — list published news, newest first. → `CT2`, `VW1`
  - *Test:* `/news` shows the seeded items, with an empty-state message when there are none.
- [ ] **show** — one item's detail via route-model binding. → `RT2`, `CT3`
  - *Test:* `/news/{id}` shows that item, including the author's name via the relationship.

### Admin: create, edit, delete (one method at a time)

- [ ] Admin resource route + `Admin\NewsController` inside the admin group. → `RT3`, `RT4`
  - *Test:* `route:list` shows `admin.news.*`, all behind `auth` + `admin`.
- [ ] **create + store** — form with an image upload; validate, store image, save record. → `CT4`, `VW3`, `VW4`
  - *Test:* submitting adds a news row and the image appears on the detail page.
  - *Test:* omitting `@csrf` gives a 419 — confirm your form has it (CSRF protection).
- [ ] **edit + update** — same form, pre-filled; `@method('PUT')`; validate and save. → `CT5`
  - *Test:* editing changes the **existing** row (no new row) and redirects.
- [ ] **destroy** — delete form with `@csrf` + `@method('DELETE')`, admin only. → `CT6`
  - *Test:* deleting removes the row and redirects back to the list.

### Tags — an optional many-to-many (extra credit)

Not required — skip if short on time. But it's easy marks: a news item can carry several tags, and a tag labels many news items. → `MR4`, `MR1`

- [ ] `php artisan make:model Tag -mf`; give `tags` a `name` column. → `WB6`, `MB3`
  - *Test:* `migrate:fresh` builds a `tags` table with a `name`.
- [ ] Add the pivot migration `news_tag` (both names, singular, alphabetical) with `news_id` + `tag_id`. → `MR4`, Conventions
  - *Test:* `migrate:fresh` builds a `news_tag` table with the two foreign keys.
- [ ] `tags()` on News and `news()` on Tag, both `belongsToMany`. → `MR4`, `MB4`
  - *Test:* Tinker: `News::first()->tags` and `Tag::first()->news` both return collections.
- [ ] Seed a handful of tags and attach some to each news item. → `MB2`
  - *Test:* after `migrate:fresh --seed`, the `news_tag` table has rows.
- [ ] In the admin news create/edit form let the admin pick tags, and `sync()` them on save. → `CT4`, `CT5`, `VW3`
  - *Test:* saving with two tags ticked writes two `news_tag` rows; unticking one removes it.
- [ ] Show a news item's tags on its public detail page. → `VW1`
  - *Test:* the tags you attached appear on `/news/{id}`.

---

## Phase 7 — FAQ (Categories & Questions)

Questions grouped by category. `Category hasMany Question` is a second **one-to-many**. Everyone reads it; admins manage both categories and questions. → `MR3`

### Data

- [ ] `php artisan make:model Category -mf` and `php artisan make:model Question -mf`. → `WB6`
  - *Test:* both sets of model, migration and factory files exist.
- [ ] Migrations: `categories.name`; `questions.question`, `questions.answer`, `questions.category_id` (FK). → `MB3`
  - *Test:* `migrate:fresh` builds both tables with the FK.
- [ ] Relationships: `questions()` (hasMany) on Category, `category()` (belongsTo) on Question. → `MR3`, `MB4`
  - *Test:* Tinker: `Category::first()->questions` returns a collection.
- [ ] Seed a few categories, each with several questions. → `MB2`
  - *Test:* after seeding, categories and their questions exist.

### Public FAQ

- [ ] Route `/faq` → `FaqController@index`, load categories **with** their questions. → `RT1`, `CT2`
  - *Test:* `/faq` responds without an N+1 storm (eager-load `with('questions')`).
- [ ] View: loop categories, nested loop questions/answers. → `VW1`
  - *Test:* every category shows with its questions grouped beneath it (control structures).

### Admin: manage categories, then questions

- [ ] Admin resource for **categories** (`Admin\CategoryController`): create/store, edit/update, destroy. → `RT4`, `CT4`, `CT5`, `CT6`
  - *Test:* an admin can add, rename, and delete a category.
- [ ] Admin resource for **questions** (`Admin\QuestionController`), each tied to a category. → `RT4`, `MR3`
  - *Test:* an admin can add a question under a chosen category, edit it, delete it.
  - *Test:* new/edited questions appear in the right group on the public `/faq`.

---

## Phase 8 — Contact Page (Form → Email to Admin)

A visitor fills a contact form; submitting emails the admin. This is the show-form / handle-submission pattern with a Mailable instead of a save. → `VW4`, `CT4`

- [ ] Route `GET /contact` → `ContactController@create` — show the form. → `RT1`, `VW4`
  - *Test:* `/contact` shows name / email / message fields with `@csrf`.
- [ ] Add client-side validation (`required`, `type="email"`) on the inputs. → `VW3`
  - *Test:* the browser blocks an empty submit before it reaches the server.
- [ ] Route `POST /contact` → `ContactController@store` — validate server-side. → `CT4`, `CT7`
  - *Test:* submitting with a bad email re-shows the form with errors and the typed-in values (`old()`).
- [ ] Build a Mailable and send it to the admin address; set the dev mailer to `log`. → `WB6`
  - *Test:* a valid submit writes the email into `storage/logs/laravel.log`.
- [ ] *(Optional, higher grade)* Also store each message in a `contact_messages` table and add an admin inbox. → `MB1`, `CT2`
  - *Test:* an admin page lists submitted messages.

---

## Phase 9 — Technical-Requirement Sweep & Delivery

This phase proves each **graded** technical requirement is actually present, then packages the deliverables. Tick each only after pointing at the exact file (you'll reuse these locations in the README).

### Prove each technical requirement

- [ ] **Two layouts** used across the site. → `VW2`
  - *Test:* name the two layout files and a page using each.
- [ ] **A component** used where it earns its keep (a form input, a card). → `VW3`, `VW5`
  - *Test:* the same component tag appears in at least two views.
- [ ] **Control structures** in Blade (loops, conditionals, empty-states). → `CT2`
  - *Test:* point at a `@foreach` and an `@if`/empty-state in a view.
- [ ] **XSS protection** — output through `{{ }}`, never `{!! !!}` on user input.
  - *Test:* a stored `<script>` in a bio/news item renders as inert text.
- [ ] **CSRF protection** — `@csrf` in every POST/PUT/DELETE form. → `CT4`, `CT6`
  - *Test:* removing a `@csrf` produces a 419 (then put it back).
- [ ] **Client-side validation** on forms. → `VW3`
  - *Test:* an empty required field is blocked by the browser.
- [ ] **All routes use controller methods**, grouped, with middleware. → `RT1`, `RT3`, `RT4`
  - *Test:* no closure routes in `web.php`; admin block is one group.
- [ ] **Resource controllers** for CRUD. → `RT4`
  - *Test:* News / Category / Question use `Route::resource`.
- [ ] **One-to-many** relationship present. → `MR3`
  - *Test:* User → News (and Category → Question) work in Tinker both directions.
- [ ] *(Optional — extra credit)* **Many-to-many** relationship present. → `MR4`
  - *Test:* News ↔ Tag via `news_tag` works in Tinker both directions.
- [ ] **Auth set** complete: login, logout, remember-me, register, password reset. → `RT5`
  - *Test:* walk all five flows once more.

### Database & delivery

- [ ] `php artisan migrate:fresh --seed` runs clean on a wiped DB. → `WB6`
  - *Test:* fresh clone + your `.env` + this one command yields a working, populated app.
- [ ] Default admin present after seeding: `admin@ehb.be` / `Password!321`. → `MB2`
  - *Test:* you can log in as the admin on a freshly seeded DB.
- [ ] README: description, per-requirement file/line references, install guide, screenshots, sources (incl. AI chat log).
  - *Test:* a classmate could clone and run it using only your README.
- [ ] Commit history shows work **per feature and per day**, with clear messages.
  - *Test:* `git log` reads like this roadmap, not like one big dump.

---

**You're done when** a fresh clone, your `.env`, and `php artisan migrate:fresh --seed` give a working app where every box above is ticked. Good luck — and keep committing.

*Source: [The Roadmap · Laravel MVC Reference Cards](https://edu.deblauwe.be/roadmap.html)*
