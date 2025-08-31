# PENSA UMaT Website

Official website for the Pentecost Students and Associates (PENSA) at the University of Mines and Technology (UMaT), Tarkwa.

## Features

- **Responsive Design**: Works on all devices (desktop, tablet, mobile)
- **Modern UI**: Clean and professional design with PENSA/UMaT colors
- **Interactive Elements**: Smooth animations and transitions
- **Easy to Update**: Simple HTML/CSS/JS structure for easy maintenance

## Pages

1. **Home**: Welcome page with quick links to other sections
2. **About**: Information about PENSA UMaT including vision, mission, and history
3. **Ministries**: Overview of all active ministries and departments
4. **Our Team**: Meet the current executives and leaders

## Technologies Used

- HTML5
- CSS3 (with CSS Variables for theming)
- JavaScript (Vanilla JS)
- Font Awesome Icons
- Google Fonts

## Getting Started

### Prerequisites

- A modern web browser (Chrome, Firefox, Safari, Edge)
- A code editor (VS Code, Sublime Text, etc.)

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/yourusername/pensa-umat.git
   ```

2. Navigate to the project directory:
   ```bash
   cd pensa-umat
   ```

3. Open `index.html` in your web browser to view the website locally.

## Project Structure

```
pensa-umat/
├── index.html          # Home page
├── about.html          # About page
├── ministries.html     # Ministries page
├── portfolio.html      # Team/Portfolio page
├── css/
│   └── style.css       # Main stylesheet
├── js/
│   └── main.js         # Main JavaScript file
└── images/             # Directory for images (create this folder)
    └── hero-bg.jpg     # Hero section background image
```

## Customization

### Colors

You can customize the color scheme by modifying the CSS variables in `css/style.css`:

```css
:root {
    --primary-color: #006633;   /* UMaT Green */
    --secondary-color: #FFD700; /* Gold */
    --text-color: #333;
    --light-bg: #f8f9fa;
    --white: #ffffff;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
```

### Adding Team Members

To add or update team members, edit the `teamMembers` array in `portfolio.html`:

```javascript
const teamMembers = [
    {
        name: 'John Doe',
        position: 'President',
        image: 'images/team/john-doe.jpg',
        category: 'presidency',
        social: {
            twitter: '#',
            facebook: '#',
            instagram: '#',
            linkedin: '#'
        }
    },
    // Add more team members here
];
```

## Deployment

The website can be deployed to any static web hosting service, such as:

- Netlify
- Vercel
- GitHub Pages
- Firebase Hosting
- AWS S3 + CloudFront

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [Font Awesome](https://fontawesome.com/) for icons
- [Google Fonts](https://fonts.google.com/) for typography
- The entire PENSA UMaT community for their support
