# Insulin Project

Welcome to the Insulin project! This project is a small system designed for testing purposes. It features three types of users: admin, co-admin, and patient. The system allows users to manage foods, their categories, and user information. Additionally, patients can track their food intake and make fasting glucose measurements, which are displayed as a curve.

## User Types

1. **Admin**: As an admin, you have full control over the system. You can promote or demote user permissions, manage all foods and their categories, and access information for any user on the website.

2. **Co-admin**: Co-admins have specific responsibilities within the system. They can add foods, manage categories, and view information for specific users. However, they do not have the ability to promote or demote user permissions.

3. **Patient**: Patients have their own set of functionalities. They can add the foods they have consumed, view and modify their personal information, and make fasting glucose measurements. The measurements are displayed as a curve for better visualization.

## Posts Section

The website also includes a section for posts from admins and co-admins. These posts provide valuable tips and information for patients. The posts are categorized to make it easier for users to find relevant content. To optimize the loading speed and performance, the system compresses images and employs lazy loading. Initially, only five posts will be loaded, and additional posts will load as the user scrolls, optimizing both performance and bandwidth.

## Image Compression

To improve performance and reduce file sizes, the system applies image compression techniques. When an admin or co-admin uploads an image for a specific food, the system automatically compresses it from 20MB to 1MB while maintaining good image quality overall.

Please note that this project is primarily for testing purposes and may require further development and enhancements for production use.

## Getting Started

To get started with the Insulin project, follow these steps:

1. Clone this repository to your local machine.

2. Install the required dependencies and set up the project according to the provided instructions.

3. Start the system and access it through your preferred web browser.

## Contributing

Contributions to this project are currently not accepted as it is intended for testing purposes. However, you are welcome to fork the repository and modify the code for your own use.