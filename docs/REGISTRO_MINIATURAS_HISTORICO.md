# Registro da alteração - Miniaturas no histórico

## O que foi alterado

Foi adicionada uma configuração administrativa para controlar se o histórico da identificação deve mostrar miniaturas ao lado de cada escolha feita na chave dicotômica.

Por padrão, a configuração fica ativada. Assim, quando a alternativa possuir imagem cadastrada, ela aparece como miniatura no histórico. Quando não houver imagem cadastrada, o sistema mostra um placeholder discreto com o texto `Sem imagem`.

## Arquivos alterados

| Arquivo | Alteração |
|---|---|
| `chave.php` | O histórico passou a guardar a imagem da alternativa escolhida e a renderizar miniatura conforme a configuração. |
| `assets/css/site-chave.css` | Foram criados estilos para a miniatura e para o placeholder do histórico. |
| `admin/configuracoes.php` | Nova tela administrativa para ativar ou desativar as miniaturas no histórico. |
| `admin/index.php`, `admin/ordens.php`, `admin/familias.php`, `admin/chaves.php`, `admin/admins.php` | Menu administrativo atualizado com o link `Configurações`. |
| `includes/db.php` | Foram criadas funções auxiliares para ler e salvar configurações do sistema. |
| `api.php` | Novo endpoint `configuracoes_chave`, usado pela tela da chave dicotômica. |
| `assets/css/admin-responsive.css` | Estilos da seção de configurações. |
| `database/entomologia.sql` | Dump atualizado com a tabela `configuracoes` e o valor padrão ativado. |

## Testes feitos

| Teste | Resultado |
|---|---|
| Validação PHP com `D:\xampp\php\php.exe -l` em `includes/db.php`, `api.php`, `chave.php` e `admin/configuracoes.php`. | Sem erros de sintaxe. |
| API `api.php?action=configuracoes_chave` com a opção ativada. | Retornou `{"exibir_miniaturas_historico":true}`. |
| Interface admin com login `professor@gmail.com` e senha `123456`. | Tela `Configurações` acessível e salvando a opção corretamente. |
| Chave `Hemiptera-Auchenorrhyncha` com miniaturas ativadas. | Histórico exibiu um item com miniatura/placeholder ao lado da escolha. |
| Chave com miniaturas desativadas no admin. | Histórico manteve o passo registrado, mas sem miniatura. |
| Restauração do padrão ativado. | Banco e API voltaram para `true`. |
| Teste mobile em `390x844`. | Histórico com miniatura/placeholder sem rolagem horizontal. |

## Observação

No banco local, as alternativas testadas ainda não possuem imagens próprias em `sim_imagem` ou `nao_imagem`. Por isso, o teste visual exibiu o placeholder `Sem imagem`. Quando essas imagens forem cadastradas no admin, o mesmo espaço passa a mostrar a miniatura da alternativa escolhida.
