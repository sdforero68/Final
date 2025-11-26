# ✅ Checklist para Despliegue en Render

## Pre-Despliegue

### Base de Datos
- [ ] Base de datos creada en Remotemysql/PlanetScale/etc
- [ ] Credenciales guardadas de forma segura
- [ ] Archivo `init.sql` ejecutado en la base de datos
- [ ] Tablas creadas correctamente (verificar con `SHOW TABLES;`)
- [ ] Categorías insertadas (verificar con `SELECT * FROM categories;`)
- [ ] Productos insertados (opcional, puedes usar `populate_products.sql`)

### Código
- [ ] Código subido a GitHub
- [ ] `swagger-manual.php` existe en `backend/api/`
- [ ] `Dockerfile` está en `backend/`
- [ ] `composer.json` está en `backend/`
- [ ] `router.php` está en `backend/`

## Configuración en Render

### Servicio
- [ ] Servicio Web creado en Render
- [ ] Repositorio conectado correctamente
- [ ] Root Directory configurado como `backend`
- [ ] Runtime configurado como `Docker`

### Variables de Entorno
- [ ] `DB_HOST` configurada
- [ ] `DB_PORT` configurada (generalmente 3306)
- [ ] `DB_NAME` configurada
- [ ] `DB_USER` configurada
- [ ] `DB_PASSWORD` configurada
- [ ] `DB_SSL` configurada (false para Remotemysql, true para PlanetScale)
- [ ] `PORT` configurada (generalmente 10000 o el que Render asigne)

## Post-Despliegue

### Verificación
- [ ] Build completado sin errores
- [ ] Health check responde: `/health.php`
- [ ] Swagger UI funciona: `/swagger-ui.php`
- [ ] Endpoints responden correctamente
- [ ] Base de datos conecta (productos listan correctamente)

### Frontend
- [ ] URL de producción actualizada en `frontend/js/api/config.js`
- [ ] Frontend desplegado en GitHub Pages
- [ ] Frontend conecta correctamente al backend en Render

## Problemas Comunes

Si algo no funciona, revisa:

1. **Logs de Render**: Render Dashboard → Logs
2. **Variables de entorno**: Verifica que estén escritas correctamente
3. **Root Directory**: Debe ser `backend`, NO `backend/` con barra
4. **Base de datos**: Verifica que las tablas existan
5. **CORS**: Verifica que la URL de Render esté en `backend/api/config.php`

## URLs Importantes

Una vez desplegado:

- **API Base**: `https://tu-servicio.onrender.com`
- **Health**: `https://tu-servicio.onrender.com/health.php`
- **Swagger UI**: `https://tu-servicio.onrender.com/swagger-ui.php`
- **Swagger JSON**: `https://tu-servicio.onrender.com/swagger.php`
- **Productos**: `https://tu-servicio.onrender.com/products/list.php`

