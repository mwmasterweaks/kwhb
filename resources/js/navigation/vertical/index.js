export default [
  {
    title: 'Dashboard',
    to: { name: 'index' },
    icon: { icon: 'tabler-smart-home' },
  },
  {
    title: 'People',
    icon: { icon: 'tabler-users' },
     children: [
      {
        title: 'Employees',
        to: { name: 'employee' },
      },
      {
        title: 'Performance Reviews'
      },
      {
        title: 'Leave Management',
        to: 'LeaveManagement',
      }
    ],
    badgeClass: 'bg-global-primary',
  },
]
