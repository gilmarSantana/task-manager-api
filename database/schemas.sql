-- Criar o banco de dados
CREATE DATABASE task_manager;

-- Criar schema de autenticação
CREATE SCHEMA IF NOT EXISTS auth;

-- Criar tabela de usuários
CREATE TABLE IF NOT EXISTS auth.users (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Criar tipo de status de tarefa
DO $$ BEGIN
    IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'task_status') THEN
        CREATE TYPE public.task_status  AS ENUM ('pending', 'in_progress', 'done');
    END IF;
END $$;

-- Criar tabela de tarefas
CREATE TABLE IF NOT EXISTS public.tasks (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL,
    title VARCHAR(150) NOT NULL,
    description TEXT, -- Removi o NOT NULL aqui caso queira tarefas só com título
    status public.task_status NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES auth.users(id) ON DELETE CASCADE
);

-- Criar índices para otimização de consultas
CREATE INDEX IF NOT EXISTS idx_user_id ON public.tasks(user_id);
CREATE INDEX IF NOT EXISTS idx_status ON public.tasks(status);


-- Função que atualiza o timestamp
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
    NEW.updated_at = CURRENT_TIMESTAMP;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Trigger que chama a função antes de UPDATE
CREATE TRIGGER set_updated_at
BEFORE UPDATE ON public.tasks
FOR EACH ROW
EXECUTE FUNCTION update_updated_at_column();


-- Comentários nas tabelas
COMMENT ON TABLE auth.users IS 'Tabela de usuários do sistema';
COMMENT ON TABLE public.tasks IS 'Tabela de tarefas dos usuários';
COMMENT ON COLUMN auth.users.password IS 'Hash da senha (bcrypt/argon2)';
COMMENT ON COLUMN public.tasks.status IS 'Status da tarefa: pending, in_progress ou done';