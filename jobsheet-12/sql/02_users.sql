-- Jobsheet 10: tabel users (petugas/admin)
CREATE TABLE IF NOT EXISTS users (
    id         SERIAL       PRIMARY KEY,
    nama       VARCHAR(100) NOT NULL,
    username   VARCHAR(50)  NOT NULL UNIQUE,
    password   VARCHAR(255) NOT NULL,           -- hash dari password_hash(), bukan plaintext
    role       VARCHAR(10)  NOT NULL DEFAULT 'petugas' CHECK (role IN ('admin', 'petugas')),
    created_at TIMESTAMP    NOT NULL DEFAULT NOW()
);

-- Token "Ingat Saya": hanya hash token yang disimpan di database.
CREATE TABLE IF NOT EXISTS remember_tokens (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    token_hash CHAR(64) NOT NULL UNIQUE,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT NOW()
);
