#!/bin/bash
# Script to set up OroCommerce message queue consumer as a systemd service

echo "Setting up OroCommerce Message Queue Consumer service..."

# Copy service file to systemd directory
sudo cp /var/www/html/oro_new/oro-message-consumer.service /etc/systemd/system/

# Reload systemd daemon
sudo systemctl daemon-reload

# Enable the service to start on boot
sudo systemctl enable oro-message-consumer.service

# Start the service
sudo systemctl start oro-message-consumer.service

# Check status
sudo systemctl status oro-message-consumer.service

echo ""
echo "Service setup complete!"
echo ""
echo "Useful commands:"
echo "  sudo systemctl status oro-message-consumer   # Check status"
echo "  sudo systemctl start oro-message-consumer    # Start service"
echo "  sudo systemctl stop oro-message-consumer     # Stop service"
echo "  sudo systemctl restart oro-message-consumer  # Restart service"
echo "  sudo journalctl -u oro-message-consumer -f   # View logs"
