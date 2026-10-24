import React from 'react'
import { createRoot } from 'react-dom/client'
import ModulesDesk from '../../../resources/js/lab/components/modules/ModulesDesk.jsx'
const config = JSON.parse(document.getElementById('qa-config').textContent)
createRoot(document.getElementById('root')).render(<ModulesDesk projectUuid={config.projectUuid} />)
