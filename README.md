<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## Smart Restroom Dashboard

The dashboard at `/` refreshes the latest ESP32 sensor reading every five seconds. It displays visits since cleaning, the two cubicles' availability, the floor-water state, and the air/odor state. It does not provide an event-log screen.

### ESP32 endpoint

Send a JSON `POST` to `/api/sensor-data` with an `X-API-KEY` header matching `IOT_API_KEY` in `.env`:

```json
{
  "people_count": 27,
  "cubicle_1_occupied": false,
  "cubicle_2_occupied": true,
  "tcs_red": 1420,
  "tcs_green": 1680,
  "tcs_blue": 1190,
  "tcs_clear": 4600,
  "water_state": "uncalibrated",
  "mq135_raw": 1380,
  "mq135_state": "uncalibrated"
}
```

Required values are `people_count`, `tcs_red`, `tcs_green`, `tcs_blue`, `tcs_clear`, and `mq135_raw`. Optional `cubicle_1_occupied` and `cubicle_2_occupied` are booleans supplied by the ESP32 (`true` means in use; `false` means available); leave either out until its ultrasonic sensor is ready. Optional `water_state` values are `dry`, `clear_water`, `muddy_water`, or `uncalibrated`; optional `mq135_state` values are `normal`, `elevated`, or `uncalibrated`. Omit either state until its calibration is known. The server stores supplied states and never infers calibration thresholds. The single IR sensor reports cumulative visit detections separately from the two cubicle states. MQ-135 data is shown as a raw analog count, not gas concentration.

Successful posts return `201`, validation errors return `422`, and an absent/incorrect API key returns `401`.

### Local development

```powershell
php artisan serve --host=0.0.0.0 --port=8000
npm run dev
```

Run `php artisan migrate` after pulling code with new migrations. Seed the initial administrator with `php artisan db:seed --class=AdminUserSeeder`; its username and password come from `RMS_ADMIN_USERNAME` and `RMS_ADMIN_PASSWORD` in the ignored `.env` file. Configure MySQL and `IOT_API_KEY` there as well.

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
