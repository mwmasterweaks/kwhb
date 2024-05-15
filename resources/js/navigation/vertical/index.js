
export default [
  {
    title: 'Dashboard',
    to: { name: 'index' },
    icon: { icon: 'tabler-smart-home' },
  },
  {
    title: 'People',
    icon: { icon: 'tabler-users', color: '#ffffff' },
    children: [
      {
        title: 'Employee',
        to: { name: 'employee' },
        group: 'People',
      },
      {
        title: 'Performance Reviews',
        group: 'People',
      },
      {
        title: 'Leave Management',
        to: 'LeaveManagement',
        group: 'People',
      },
    ],
    badgeClass: 'bg-global-primary',
  },
]
