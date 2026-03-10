### Banner Upload Implementation
- Added  (temporary upload) and  (stored path) properties.
- Implemented  with existence guard and cache clearing.
- In , banner processing happens before other settings to ensure it's saved even if other logic fails (or vice versa, though here it's sequential).
- Used  model for persistent storage of the banner path.
### Banner Upload Implementation
- Added zakatBannerImage (temporary upload) and existingZakatBanner (stored path) properties.
- Implemented deleteZakatBanner() with existence guard and cache clearing.
- In saveZakat(), banner processing happens before other settings to ensure it's saved even if other logic fails (or vice versa, though here it's sequential).
- Used AppSetting model for persistent storage of the banner path.
