import pandas as pd
import matplotlib.pyplot as plt
import matplotlib.animation as animation
from datetime import datetime
import time

# Initialize the figure and axis
fig, ax = plt.subplots(figsize=(10, 6))


# Function to load and update the data
def get_data():
    # Replace this with how you load your data (e.g., from a file, database, etc.)
    data = pd.read_csv("data.csv")
    data["timestamp"] = pd.to_datetime(data["timestamp"])
    return data


# Function to update the plot
def update_plot(i):
    data = get_data()  # Get the updated data

    # Clear the current plot
    ax.clear()

    # Plot the heart_rate vs time
    ax.plot(data["timestamp"], data["heart_rate"], marker="o", linestyle="-", color="b")

    # Add labels and title
    ax.set_xlabel("Time")
    ax.set_ylabel("Heart Rate")
    ax.set_title("Heart Rate over Time")

    # Format x-axis
    plt.xticks(rotation=45)
    plt.tight_layout()


# Set up the animation to update every 5 seconds
ani = animation.FuncAnimation(fig, update_plot, interval=5000)  # 5000 ms = 5 seconds

# Show the plot
plt.show()
