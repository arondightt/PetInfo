-- ================================================================
-- PetInfo — Schema do Banco de Dados
-- PostgreSQL via Supabase
-- ================================================================

-- 1. TUTORES
CREATE TABLE tutores (
    id            UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    nome          VARCHAR(100) NOT NULL,
    email         VARCHAR(100) UNIQUE NOT NULL,
    senha_hash    VARCHAR(255) NOT NULL,
    whatsapp      VARCHAR(20) NOT NULL,
    instagram     VARCHAR(100),                   -- opcional, sem o @
    plano         VARCHAR(20) DEFAULT 'free',
    ativo         BOOLEAN DEFAULT TRUE,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. PETS
CREATE TABLE pets (
    id          UUID PRIMARY KEY DEFAULT gen_random_uuid(),
    tutor_id    UUID NOT NULL REFERENCES tutores(id) ON DELETE CASCADE,
    nome        VARCHAR(50) NOT NULL,
    especie     VARCHAR(20),
    raca            VARCHAR(100),
    data_nascimento DATE,
    recompensa  VARCHAR(100),
    foto_url    VARCHAR(500),
    observacoes TEXT,
    ativo       BOOLEAN DEFAULT TRUE,
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 3. COLEIRAS (Tags NFC/QR físicas)
CREATE TABLE coleiras (
    id          VARCHAR(12) PRIMARY KEY,
    pet_id      UUID REFERENCES pets(id) ON DELETE SET NULL,
    status      VARCHAR(20) DEFAULT 'ativa',
    criado_em   TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4. HISTORICO_LOCALIZACOES
CREATE TABLE historico_localizacoes (
    id             BIGSERIAL PRIMARY KEY,
    coleira_id     VARCHAR(12) NOT NULL REFERENCES coleiras(id) ON DELETE CASCADE,
    cidade         VARCHAR(100),
    estado         VARCHAR(2),
    bairro         VARCHAR(100),
    latitude       NUMERIC(10, 8),
    longitude      NUMERIC(11, 8),
    precisao       VARCHAR(20), -- 'exata' (GPS) ou 'aproximada' (selecionada manual ou geo básica)
    data_hora      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ================================================================
-- ÍNDICES
-- ================================================================
CREATE INDEX idx_pets_tutor        ON pets (tutor_id);
CREATE INDEX idx_coleiras_pet      ON coleiras (pet_id);
CREATE INDEX idx_historico_coleira ON historico_localizacoes (coleira_id);
CREATE INDEX idx_rate_limit        ON historico_localizacoes (coleira_id, data_hora DESC);
