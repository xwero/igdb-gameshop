CREATE TABLE pim_product_covers (
                             id SERIAL PRIMARY KEY,
                             product_id BIGINT NOT NULL REFERENCES pim_products(id) ON DELETE CASCADE,
                             url VARCHAR(255) NOT NULL,
                             width INT NOT NULL,
                             height INT NOT NULL,
                             created_at TIMESTAMP WITH TIME ZONE DEFAULT now(),
                             updated_at TIMESTAMP WITH TIME ZONE DEFAULT now()
);

CREATE INDEX idx_pim_product_covers_product_id ON pim_product_covers(product_id);

CREATE OR REPLACE FUNCTION pim_product_covers_updated_at_trigger()
RETURNS TRIGGER AS $$
BEGIN
  NEW.updated_at = now();
RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER trg_pim_product_covers_updated_at
    BEFORE UPDATE ON pim_product_covers
    FOR EACH ROW
    EXECUTE FUNCTION pim_product_covers_updated_at_trigger();