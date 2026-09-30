# API Documentation

## Sistem Informasi Akademik

### Router Map

| Method | Endpoint | Controller | Method |
|---|---|---|---|
| POST | `/register` | AuthController | `register()` |
| POST | `/login` | AuthController | `login()` |
| GET | `/mahasiswa` | MahasiswaController | `index()` |
| POST | `/mahasiswa` | MahasiswaController | `store()` |
| PUT | `/mahasiswa` | MahasiswaController | `update()` |
| DELETE | `/mahasiswa` | MahasiswaController | `destroy()` |

---

## Endpoint: POST /register

**Controller:** `AuthController::register()`

**Deskripsi:**

Digunakan untuk melakukan pendaftaran pengguna baru.

**Headers:**

```text
Content-Type: application/json