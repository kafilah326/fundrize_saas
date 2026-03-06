### Patterns and Conventions
- **Image Processing**: Consistent use of `Intervention\Image` for processing uploads.
- **File Naming**: Using `uniqid() . '.jpg'` for processed images.
- **Old File Cleanup**: Manual deletion of old image files when updating, specifically checking for local paths (not `http`).
- **Meta Tags**: Standardizing OG image type to `image/jpeg`.
