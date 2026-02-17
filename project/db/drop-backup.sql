-- ============================================
-- SCRIPT: drop-backup.sql
-- Date: 17-02-2026
-- Description: Suppression des tables de backup
-- Usage: Exécuter avant de réimporter donnée.sql
--        pour forcer la recréation du backup propre
-- ============================================

DROP TABLE IF EXISTS s3fin_besoin_backup;
DROP TABLE IF EXISTS s3fin_don_backup;
