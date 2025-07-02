# 2.2.0 - 30/06/2025
- Ajuste na código aberto.

# 2.1.3 - 05/02/2025
- Ajuste no script de confirmação de pagamento evitando erros de calculo para faturas mescladas;
- Melhoria na verificação de pagamento via webhook;
- Ajuste na confirmação de pagamento para evitar confirmações de pagamento duplicadas.

# 2.1.2 - 03/02/2025
- Correção de erro crítico ao cancelar fatura.

# 2.1.1 - 24/01/2025
- Ajuste para cobrança em data de vencimento anterio a data de criação do PIX;
- Adição de logs para caso exceda a data limite para pagamento do PIX;
- Melhoria na disposição de informações sobre o cálculo de juros.

# 2.1.0 - 16/12/2024
- Adicionar compatibilidade com cobranças pós vencimento;
- Configuração de cobrança de multa e juros para faturas vencidas.

# 2.0.0 - 08/11/23
- Adicionar compatibilidade com a API V2
- Adicionar verificação para garantir marcação da fatura como Reembolsada quando necessário
- Adicionar verificação para identificar pagamento de fatura antes de cancelá-la.
- Melhorias no cálculo de desconto
- Exibir porcentagem de desconto na fatura

# 1.3.0 09/08/23
- Adicionar configuração para custom field misto de CPF e CNPJ
- Adicionar configurações para definir valor mínimo e valor máximo para pagamento com o módulo
- Melhorias no layout das configurações
- Atualizar configuração "Expiração do Pix" para ser definida em dias

# 1.2.0 05/07/23
- Simplificar estrutura do gateway
- Adicionar botão para compartilhar código do Pix
- Adicionar botões e configurações para botão de confirmação manual de pagamento
- Adicionar funcionalidade de desconto por produto

# 1.1.0 - 05/05/23
- Implementar desconto por pagamento via Pix
- Adicionar tooltip para exibição do código do Pix
- Corrigir confirmação de pagamento quando fatura tem desconto

# 1.0.0 - 20/04/23

- Confirmação automática de pagamento.
- Reembolso.
