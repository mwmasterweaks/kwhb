
export default [
  {
    title: 'Dashboard',
    to: { name: 'index' },
    icon: { icon: 'tabler-smart-home' },
    category: 'Pages',
  },
  {
    title: 'People',
    icon: { icon: 'tabler-users', color: '#ffffff' },
    category: 'Pages',
    children: [
      {
        title: 'Employee',
        to: { name: 'employee' },
        group: 'People',
        category: 'Pages',
      },
      {
        title: 'Performance Reviews',
        group: 'People',
        category: 'Pages',
      },
      {
        title: 'Leave Management',
        to: 'LeaveManagement',
        group: 'People',
        category: 'Pages',
      },
    ],
    badgeClass: 'bg-global-primary',
  },
]
