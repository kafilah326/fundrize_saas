### Decisions
- **Unified Processing**: Decided to convert all uploaded images (programs and foundation logo) to JPEG 1200x630 to ensure valid OG image content-type and dimensions.
- **Storage Strategy**: Used `Storage::disk('public')->put()` for processed images instead of the default `store()` method to handle the Intervention Image object.
