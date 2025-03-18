// Script principal
document.addEventListener("DOMContentLoaded", () => {
  // Inicializa os tooltips do Bootstrap
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl))

  // Inicializa os popovers do Bootstrap
  var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
  var popoverList = popoverTriggerList.map((popoverTriggerEl) => new bootstrap.Popover(popoverTriggerEl))

  // Função para confirmar exclusão
  window.confirmDelete = (message) => confirm(message || "Tem certeza que deseja excluir este item?")

  // Função para confirmar ação
  window.confirmAction = (message) => confirm(message || "Tem certeza que deseja realizar esta ação?")

  // Função para formatar valores monetários
  const formatCurrency = (value) => new Intl.NumberFormat("pt-BR", { style: "currency", currency: "BRL" }).format(value)

  // Função para formatar datas
  const formatDate = (dateString) => {
    const options = { day: "2-digit", month: "2-digit", year: "numeric" }
    return new Date(dateString).toLocaleDateString("pt-BR", options)
  }

  // Função para formatar data e hora
  const formatDateTime = (dateTimeString) => {
    const options = { day: "2-digit", month: "2-digit", year: "numeric", hour: "2-digit", minute: "2-digit" }
    return new Date(dateTimeString).toLocaleDateString("pt-BR", options)
  }

  // Aplica formatação de moeda aos elementos com a classe 'currency'
  document.querySelectorAll(".currency").forEach((element) => {
    const value = Number.parseFloat(element.textContent)
    if (!isNaN(value)) {
      element.textContent = formatCurrency(value)
    }
  })

  // Aplica formatação de data aos elementos com a classe 'date'
  document.querySelectorAll(".date").forEach((element) => {
    const dateString = element.textContent
    if (dateString) {
      element.textContent = formatDate(dateString)
    }
  })

  // Aplica formatação de data e hora aos elementos com a classe 'datetime'
  document.querySelectorAll(".datetime").forEach((element) => {
    const dateTimeString = element.textContent
    if (dateTimeString) {
      element.textContent = formatDateTime(dateTimeString)
    }
  })
})

