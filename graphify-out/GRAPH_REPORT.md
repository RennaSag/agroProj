# Graph Report - D:\xampp\htdocs\agroProj-main  (2026-05-27)

## Corpus Check
- Corpus is ~15,960 words - fits in a single context window. You may not need a graph.

## Summary
- 73 nodes · 98 edges · 21 communities (20 shown, 1 thin omitted)
- Extraction: 93% EXTRACTED · 6% INFERRED · 1% AMBIGUOUS · INFERRED: 6 edges (avg confidence: 0.84)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- [[_COMMUNITY_Admin Authentication and CRUD|Admin Authentication and CRUD]]
- [[_COMMUNITY_Public Identification Flow|Public Identification Flow]]
- [[_COMMUNITY_Student Identification Use Cases|Student Identification Use Cases]]
- [[_COMMUNITY_Administrator Use Cases|Administrator Use Cases]]
- [[_COMMUNITY_Specimen Photo Evidence|Specimen Photo Evidence]]
- [[_COMMUNITY_Use Case Diagram Context|Use Case Diagram Context]]

## God Nodes (most connected - your core abstractions)
1. `PDO Database Connection Service` - 9 edges
2. `Dichotomous Key Step Management` - 9 edges
3. `Orders Table` - 9 edges
4. `Families Table` - 8 edges
5. `Admin Session Authorization Guard` - 7 edges
6. `Protected Administration Dashboard` - 7 edges
7. `Family CRUD Management` - 7 edges
8. `Order Details API Action` - 6 edges
9. `Administrator Login Authentication` - 6 edges
10. `Order CRUD Management` - 6 edges

## Surprising Connections (you probably didn't know these)
- `Admin Session Authorization Guard` --implements--> `Session Based Administrative Authentication`  [EXTRACTED]
  includes/db.php → readme.md
- `Interactive Specimen Match Decision UI` --implements--> `Specimen Match Dichotomous Navigation`  [EXTRACTED]
  chave.php → readme.md
- `Admin Password Verification Probe` --semantically_similar_to--> `Administrator Login Authentication`  [INFERRED] [semantically similar]
  teste.php → admin/login.php
- `Administrator Login Authentication` --implements--> `Session Based Administrative Authentication`  [EXTRACTED]
  admin/login.php → readme.md
- `Dichotomous Key Step Management` --implements--> `Administrative Content Management Panel`  [EXTRACTED]
  admin/chaves.php → readme.md

## Hyperedges (group relationships)
- **Public Specimen Identification Flow** — index_public_order_catalog, api_list_active_orders, api_get_order_details, chave_specimen_match_ui, api_get_key_steps, entomologia_orders_table, entomologia_families_table, entomologia_key_steps_table, readme_specimen_match_design [EXTRACTED 1.00]
- **Session Protected Administration Flow** — check_auth_admin_router, login_admin_authentication, db_admin_session_guard, admin_index_dashboard, admins_admin_management, ordens_order_management, familias_family_management, chaves_key_step_management, logout_admin_action, entomologia_admins_table, readme_session_auth_design [EXTRACTED 1.00]
- **Admin Authored Public Taxonomy and Key Data** — ordens_order_management, familias_family_management, chaves_key_step_management, entomologia_orders_table, entomologia_families_table, entomologia_key_steps_table, api_list_active_orders, api_get_order_details, api_get_key_steps, chave_specimen_match_ui [INFERRED 0.90]
- **Casos de uso contidos em Sistema de Chave Entomologica** — diagrama_de_caso_de_uso_sistema_de_chave_entomologica, diagrama_de_caso_de_uso_visualizar_listagem_de_ordens, diagrama_de_caso_de_uso_visualizar_descricao_da_ordem, diagrama_de_caso_de_uso_iniciar_chave_dicotomica, diagrama_de_caso_de_uso_responder_passos_de_filtragem, diagrama_de_caso_de_uso_visualizar_familia_identificada, diagrama_de_caso_de_uso_refazer_identificacao, diagrama_de_caso_de_uso_fazer_login, diagrama_de_caso_de_uso_gerenciar_ordens, diagrama_de_caso_de_uso_gerenciar_familias, diagrama_de_caso_de_uso_gerenciar_chaves_dicotomicas, diagrama_de_caso_de_uso_gerenciar_administradores [EXTRACTED 1.00]
- **Fluxo de identificacao conectado por include** — diagrama_de_caso_de_uso_iniciar_chave_dicotomica, diagrama_de_caso_de_uso_responder_passos_de_filtragem, diagrama_de_caso_de_uso_visualizar_familia_identificada, diagrama_de_caso_de_uso_refazer_identificacao [EXTRACTED 1.00]

## Communities (21 total, 1 thin omitted)

### Community 0 - "Admin Authentication and CRUD"
Cohesion: 0.21
Nodes (17): Protected Administration Dashboard, Administrator Account Management, Admin Entry Authentication Router, Environment Configuration Loader, Admin Session Authorization Guard, Shared Insect Image Upload Service, PDO Database Connection Service, Admins Table (+9 more)

### Community 1 - "Public Identification Flow"
Cohesion: 0.31
Nodes (14): Family Details API Action, Dichotomous Key Steps API Action, Order Details API Action, List Active Orders API Action, Public JSON API Router, Interactive Specimen Match Decision UI, Dichotomous Key Step Management, Key Step Image Column Compatibility Upgrade (+6 more)

### Community 2 - "Student Identification Use Cases"
Cohesion: 0.43
Nodes (7): Aluno, Iniciar chave dicotomica, Refazer identificacao, Responder passos de filtragem, Visualizar descricao da ordem, Visualizar familia identificada, Visualizar listagem de ordens

### Community 3 - "Administrator Use Cases"
Cohesion: 0.33
Nodes (6): Administrador, Fazer login, Gerenciar administradores, Gerenciar chaves dicotomicas, Gerenciar familias, Gerenciar ordens

### Community 5 - "Specimen Photo Evidence"
Cohesion: 0.50
Nodes (5): Green Leaf Surface, Possible Leafhopper-Like Hemipteran, Specimen Photograph, Translucent Veined Wings, Pale Yellow Winged Insect

## Ambiguous Edges - Review These
- `Pale Yellow Winged Insect` → `Possible Leafhopper-Like Hemipteran`  [AMBIGUOUS]
  uploads/insetos/ordem_6a166ad182e21.jpg · relation: conceptually_related_to

## Knowledge Gaps
- **12 isolated node(s):** `Environment Configuration Loader`, `Password Hash Generation Utility`, `Sistema de Chave Entomologica (Diagrama de Casos de Uso)`, `Visualizar listagem de ordens`, `Visualizar descricao da ordem` (+7 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **1 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **What is the exact relationship between `Pale Yellow Winged Insect` and `Possible Leafhopper-Like Hemipteran`?**
  _Edge tagged AMBIGUOUS (relation: conceptually_related_to) - confidence is low._
- **Why does `PDO Database Connection Service` connect `Admin Authentication and CRUD` to `Public Identification Flow`?**
  _High betweenness centrality (0.042) - this node is a cross-community bridge._
- **Why does `Dichotomous Key Step Management` connect `Public Identification Flow` to `Admin Authentication and CRUD`?**
  _High betweenness centrality (0.028) - this node is a cross-community bridge._
- **Why does `Admin Session Authorization Guard` connect `Admin Authentication and CRUD` to `Public Identification Flow`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **What connects `Environment Configuration Loader`, `Password Hash Generation Utility`, `Specimen Match Dichotomous Navigation` to the rest of the system?**
  _14 weakly-connected nodes found - possible documentation gaps or missing edges._