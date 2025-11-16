export function formatLabel(label) {
  const text = String(label).toLowerCase()

  if (text.includes('previous') || text.includes('anterior')) {
    return '<<'
  }

  if (text.includes('next') || text.includes('próximo')) {
    return '>>'
  }

  return label
}