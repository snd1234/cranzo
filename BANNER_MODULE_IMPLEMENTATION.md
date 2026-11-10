# Banner module implementation

There was no banner table or admin screen. The homepage used one fixed image. Banner management is now a database-backed admin section, and the homepage reads from it.

## Admin

Website Management → Banner Management, under `/system-auth`, requires an admin login.

- List shows the image, title, subtitle, button text, display order, status, and created date. It can be searched by title and filtered by status, and the list is paginated.
- Add and edit accept title, subtitle, description, desktop image, optional mobile image, button text, button URL, display order, and status. Status defaults to active.
- Desktop image is required only when adding. Replacing an image keeps the previous file until the new one is saved, then removes the old file.
- View is read-only.
- Activate and deactivate switch status without deleting the image.
- Delete asks for confirmation, removes the row, then removes the image files. A missing file does not stop the delete.

Button URLs must be a site path starting with `/` or an `http`/`https` address.

Images are JPG, PNG, or WEBP, up to 4 MB, stored as generated filenames in `public/uploads/banners`. That matches the shop’s existing public upload folder because `UPLOADS_URL` is empty.

## Homepage

Active banners are ordered by `sort_order`, then `id`, and limited to 5. The slider shows the title, subtitle, description, and button only when those values exist. Mobile screens use the mobile image when one is uploaded, otherwise the desktop image. One banner hides the arrows and dots. No active banners keeps the original `banner.jpg` link to the product list. The slider is one script on the homepage; Swiper is not loaded because the current storefront was not using it.

## Verification

Six active banners and one inactive banner were inserted. The homepage included orders 1 through 5, omitted order 6 and the inactive banner, and showed next/previous controls. After those rows and the test file were removed, the homepage showed `banner.jpg` again. A logged-out request to the banner list redirects to the admin login. A `javascript:` button URL fails validation.

## Files

- `app/Models/Banner.php`
- `app/Http/Controllers/BannerController.php`
- `app/Http/Requests/Admin/BannerRequest.php`
- `app/Http/Controllers/HomeController.php`
- `resources/views/admin/banners/index.blade.php`
- `resources/views/admin/banners/form.blade.php`
- `resources/views/admin/banners/show.blade.php`
- `resources/views/index.blade.php`
- `resources/views/layout/admin.blade.php`
- `database/migrations/2026_11_10_140500_create_banners_table.php`
- `routes/web.php`
