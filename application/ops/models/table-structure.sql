CREATE TABLE ops.<kogas>_cb (
  id SERIAL, 
  nomor VARCHAR(200) NOT NULL, 
  waktu_pembuatan TIMESTAMP WITHOUT TIME ZONE, 
  waktu_sebenarnya TIMESTAMP WITHOUT TIME ZONE, 
  kotama VARCHAR(20), 
  pangkalan_aju INTEGER, 
  terpilih BOOLEAN DEFAULT false, 
  team_id INTEGER, 
  durasi_laut DOUBLE PRECISION, 
  durasi_udara DOUBLE PRECISION, 
  CONSTRAINT <kogas>_cb_pkey PRIMARY KEY(id)
) WITHOUT OIDS;

CREATE TABLE ops.<kogas>_<rute> (
  id SERIAL, 
  cb_id INTEGER NOT NULL, 
  nama VARCHAR(100), 
  durasi DOUBLE PRECISION, 
  CONSTRAINT <kogas>_<rute>_pkey PRIMARY KEY(id), 
  CONSTRAINT <kogas>_<rute>_fk FOREIGN KEY (cb_id)
    REFERENCES ops.<kogas>_cb(id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
    NOT DEFERRABLE
) WITHOUT OIDS;

COMMENT ON COLUMN ops.<kogas>_<rute>.durasi
IS 'lama waktu diperlukan untuk menjelajah dari titik awal sampai akhir (dalam JAM)';

CREATE TABLE ops.<kogas>_<rute>_formasi (
  rute_id INTEGER NOT NULL, 
  urutan INTEGER NOT NULL, 
  simbol_taktis VARCHAR(200), 
  x INTEGER, 
  y INTEGER, 
  singkatan VARCHAR(50), 
  CONSTRAINT <kogas>_<rute>_formasi_pkey PRIMARY KEY(rute_id, urutan), 
  CONSTRAINT <kogas>_<rute>_formasi_fk FOREIGN KEY (rute_id)
    REFERENCES ops.<kogas>_<rute>(id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
    NOT DEFERRABLE
) WITHOUT OIDS;

COMMENT ON COLUMN ops.<kogas>_<rute>_formasi.x
IS 'pixel';

COMMENT ON COLUMN ops.<kogas>_<rute>_formasi.y
IS 'pixel';

COMMENT ON COLUMN ops.<kogas>_<rute>_formasi.singkatan
IS 'singkatan simbol taktis';

CREATE TABLE ops.<kogas>_<rute>_titik (
  rute_id INTEGER NOT NULL, 
  urutan INTEGER NOT NULL, 
  nama VARCHAR(100), 
  longitude DOUBLE PRECISION, 
  latitude DOUBLE PRECISION, 
  kecepatan DOUBLE PRECISION, 
  CONSTRAINT <kogas>_<rute>_titik_pkey PRIMARY KEY(rute_id, urutan), 
  CONSTRAINT <kogas>_<rute>_titik_fk FOREIGN KEY (rute_id)
    REFERENCES ops.<kogas>_<rute>(id)
    ON DELETE CASCADE
    ON UPDATE RESTRICT
    NOT DEFERRABLE
) WITHOUT OIDS;

COMMENT ON COLUMN ops.<kogas>_<rute>_titik.kecepatan
IS 'dalam km/h';
