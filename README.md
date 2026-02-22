# Curumin RES 🌱

O **Curumin RES** é um sistema moderno de gestão de relacionamento e registros de saúde focado especificamente no atendimento e acompanhamento de populações de aldeias e comunidades indígenas. 

Este projeto foi construído utilizando o framwework **Laravel** e banco de dados **PostgreSQL**, além de focar em uma experiência de usuário (UX) premium baseada em Glassmorphism, CSS nativo super polido, e mapas geolocalizados automáticos.

## 🎯 Objetivo do Projeto
O sistema tem como meta fornecer aos profissionais de saúde, médicos e gestores uma plataforma rápida, fluída e visualmente rica para cadastrar e acompanhar:
*   As **Comunidades e Aldeias**, distribuindo as informações demográficas no mapa para calcular o raio de ação e volume de pacientes.
*   As parcelas infantis da população (1 a 18 anos), ajudando no controle e gestão preventiva médica e profilática infantil.
*   Futuros registros numéricos atrelados a tabelas relacionais em banco SQL focado na robustez.

## ✨ Funcionalidades Atuais

*   **🛡️ Autenticação Fechada:** Sistema restrito apenas por credencias previamente registradas em banco (Login Médico via `adimin` : `natallya`), protegendo informações sensíveis por _Middleware_.
*   **🧑‍⚕️ Perfis de Profissionais:** Upload dinâmico local de imagem de perfil, armazenamento via `storage/public/profile-photos`, associando e ilustrando globalmente a foto do médico que está logado.
*   **📍 Gestor Demográfico Georeferenciado (Comunidades):**
    *   Leitura de Totais Populacionais entre jovens (Separados por faixas: 1-5 anos, 5-10 anos e 10-18 anos).
    *   **Dashboard Leaflet (Mapa de Pins):** Identificação exata da Aldeia atrelado às suas coordenadas mapeadas.
    *   **Geocodificação Automática OpenStreetMap (Nominatim API):** Se as latitudes e longitudes de uma comunidade forem ignoradas durante o cadastro mas ela possuir o nome e o endereço detalhados inseridos, a API converte o endereço em coordenadas e salva automaticamente.
    *   _CRUD (Create, Read, Update, Delete)_ completo e validado direto pela Interface de Comunidades.
*   **💅 UI/UX Premium:** Cores extraídas da natureza (_Verde Bandeira, Verde Erva, Tons Terrosos e Laranjas_), Transições Animadas `fadeIn` padrão, Bordas macias, e uso de iconografia global limpa _(Font-Awesome 6.4)_.

## 🛠️ Tecnologias e Dependências
*   [PHP 8.2+](https://www.php.net/)
*   [Laravel 12+](https://laravel.com/)
*   [PostgreSQL](https://www.postgresql.org/)
*   [Leaflet.JS](https://leafletjs.com/) (Mapas)
*   Vanilla CSS (Global Stylesheet Modularizado `public/css/style.css`)
*   Font Awesome + Google Fonts (Inter)

## 🚀 Como instalar e rodar (Para Desenvolvedores)

**1. Clone o repositório ou acesse a pasta raiz**
```bash
cd crm_kid
```

**2. Instalar as dependências do Laravel**
```bash
composer install
```

**3. Configure o Banco de Dados Postgre**
Configure dentro do `.env`:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=crm_kid
DB_USERNAME=postgres
DB_PASSWORD=sua_senha
```

**4. Execute as Migrações e Seeds (Alimentador do Banco)**
```bash
php artisan migrate --seed
php artisan storage:link
```
_(A seed gerará o usuário de login obrigatório nativamente, bem como comunidades modelo)._

**5. Inicie o Servidor Interno**
```bash
php artisan serve
```
Acesse `http://localhost:8000` em seu navegador padrão.

---
Feito com dedicação para gerenciar a saúde de quem preserva as florestas brasileiras. 💚
