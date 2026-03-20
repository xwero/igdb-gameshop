CREATE TABLE pim_product (
    id SERIAL PRIMARY KEY,
    igdb_id INTEGER,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
    updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

CREATE UNIQUE INDEX ON pim_product (igdb_id);

CREATE OR REPLACE FUNCTION pim_product_updated_at_trigger()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = now();
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_pim_product_updated_at
BEFORE UPDATE ON pim_product
FOR EACH ROW
EXECUTE FUNCTION pim_product_updated_at_trigger();