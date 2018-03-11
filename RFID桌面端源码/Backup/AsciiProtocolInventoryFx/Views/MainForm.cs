//-----------------------------------------------------------------------
// <copyright file="MainForm.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2014 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory.Views
{
    using System;
    using System.Collections.Generic;
    using System.ComponentModel;
    using System.Data;
    using System.Drawing;
    using System.Linq;
    using System.Text;
    using System.Windows.Forms;
    
    using TechnologySolutions.Rfid.AsciiProtocol;
    using ViewModels;

    public partial class MainForm : Form
    {
        /// <summary>
        /// Handles conencting to the reader
        /// </summary>
        private ConnectViewModel connectViewModel;

        /// <summary>
        /// Handles commanding the reader
        /// </summary>
        private MainViewModel viewModel;

        /// <summary>
        /// Initializes a new instance of the MainForm class
        /// </summary>
        public MainForm()
        {
            this.InitializeComponent();

            this.viewModel = Service.Instance.MainViewModel;
            this.viewModel.TransponderMessage += this.ViewModel_TransponderMessage;
            this.viewModel.BarcodeMessage += this.ViewModel_BarcodeMessage;
            this.viewModel.ResponseLine += this.ViewModel_ResponseLine;

            // TODO: this.viewModel.PropertyChanged 
            this.connectViewModel = Service.Instance.ConnectViewModel;
            this.connectViewModel.PropertyChanged += this.ConnectViewModel_PropertyChanged;

            // Create a menu of session values
            this.querySessionComboBox.DataSource = Enum.GetValues(typeof(QuerySession));

            // Use the description when displaying to the user
            this.querySessionComboBox.FormattingEnabled = true;
            this.querySessionComboBox.Format += delegate(object sender, ListControlConvertEventArgs e)
            {
                e.Value = ((QuerySession)e.Value).Description();
            };

            this.queryTargetComboBox.DataSource = Enum.GetValues(typeof(QueryTarget));
            this.queryTargetComboBox.FormattingEnabled = true;
            this.queryTargetComboBox.Format += delegate(object sender, ListControlConvertEventArgs e)
            {
                e.Value = ((QueryTarget)e.Value).Description();
            };
            
            this.Load += this.Form_Load;
        }

        /// <summary>
        /// Gets or sets the list of available com ports
        /// </summary>
        public IEnumerable<string> PortNames
        {
            get
            {
                foreach (ToolStripDropDownItem item in this.portToolStripMenuItem.DropDownItems)
                {
                    yield return item.Name;
                }
            }

            set
            {
                string selectedPortName;

                selectedPortName = this.connectViewModel.PortName;
                this.portToolStripMenuItem.DropDownItems.Clear();
                foreach (string portName in value)
                {
                    ToolStripMenuItem menuItem;

                    menuItem = new ToolStripMenuItem()
                    {
                        Text = portName,
                        Checked = selectedPortName.Equals(portName)
                    };

                    menuItem.Click += delegate(object sender, EventArgs e)
                    {
                        foreach (ToolStripMenuItem item in this.portToolStripMenuItem.DropDownItems)
                        {
                            item.Checked = false;
                        }

                        this.connectViewModel.PortName = (sender as ToolStripMenuItem).Text;
                        (sender as ToolStripMenuItem).Checked = true;
                    };

                    this.portToolStripMenuItem.DropDownItems.Add(menuItem);
                }
            }
        }

        private void Form_Load(object sender, EventArgs e)
        {
            this.PortNames = this.connectViewModel.PortNames;

            // Configure minimum sizes here to work round a UI designer bug
            this.resultsSplitContainer.Panel1MinSize = 400;
            this.resultsSplitContainer.Panel2MinSize = 300;
            this.mainSplitContainer.Panel1MinSize = 120;
            this.mainSplitContainer.Panel2MinSize = 240;

            this.mainSplitContainer.Panel1Collapsed = !this.viewModel.IsProtocolResponseVisible;
            this.showProtocolResponsesToolStripMenuItem.Checked = this.viewModel.IsProtocolResponseVisible;

            this.ConnectViewModel_PropertyChanged(sender, new PropertyChangedEventArgs("IsConnected"));
        }

        private void ExitToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.Close();
        }

        private void ConnectToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.connectViewModel.Connect();
        }

        private void DisconnectToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.connectViewModel.Disconnect();
        }

        private void RefreshPortsToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.connectViewModel.RefreshPorts();
            this.PortNames = this.connectViewModel.PortNames;
        }

        private void ScanRfidCommandButton_Click(object sender, EventArgs e)
        {
            System.Threading.ThreadPool.QueueUserWorkItem(delegate(object state)
            {
                // don't block the UI while the command executes
                this.viewModel.ExecuteInventoryCommandSynchronously();
            });
        }

        private void scanRfidAsyncButton_Click(object sender, EventArgs e)
        {
            System.Threading.ThreadPool.QueueUserWorkItem(delegate(object state)
            {
                // don't block the UI while the command executes
                this.viewModel.ExecuteInventoryCommandAsynchronously();
            });
        }       

        private void ScanBarcodeCommandButton_Click(object sender, EventArgs e)
        {
            System.Threading.ThreadPool.QueueUserWorkItem(delegate(object state)
            {
                // don't block the UI while the command executes
                this.viewModel.ExecuteBarcodeCommandSynchronously();
            });
        }

        private void ScanBarcodeAsyncButton_Click(object sender, EventArgs e)
        {
            System.Threading.ThreadPool.QueueUserWorkItem(delegate(object state)
            {
                // don't block the UI while the command executes
                this.viewModel.ExecuteBarcodeCommandAsynchronously();
            });
        }

        private void AbortBarcodeButton_Click(object sender, EventArgs e)
        {
            System.Threading.ThreadPool.QueueUserWorkItem(delegate(object state)
            {
                this.viewModel.ExecuteAbortCommand();
            });
        }

        private void ViewModel_TransponderMessage(object sender, TextEventArgs e)
        {
            if (this.InvokeRequired)
            {
                this.Invoke(new EventHandler<TextEventArgs>(this.ViewModel_TransponderMessage), new object[] { sender, e });
            }
            else
            {
                this.DisplayText(this.transponderListBox, e.Text);
            }
        }

        private void ViewModel_BarcodeMessage(object sender, TextEventArgs e)
        {
            if (this.InvokeRequired)
            {
                this.Invoke(new EventHandler<TextEventArgs>(this.ViewModel_BarcodeMessage), new object[] { sender, e });
            }
            else
            {
                this.DisplayText(this.barcodeListBox, e.Text);
            }
        }

        private void ViewModel_ResponseLine(object sender, TextEventArgs e)
        {
            if (this.InvokeRequired)
            {
                this.Invoke(new EventHandler<TextEventArgs>(this.ViewModel_ResponseLine), new object[] { sender, e });
            }
            else
            {
                this.DisplayText(this.responsesListBox, e.Text);
            }
        }

        private void ConnectViewModel_PropertyChanged(object sender, PropertyChangedEventArgs e)
        {
            if (this.InvokeRequired)
            {
                this.Invoke(new PropertyChangedEventHandler(this.ConnectViewModel_PropertyChanged), new object[] { sender, e });
            }
            else
            {
                this.connectToolStripMenuItem.Enabled = this.connectViewModel.CanConnect();
                this.disconnectToolStripMenuItem.Enabled = this.connectViewModel.CanDisconnect();
                this.readerToolStripMenuItem.Enabled = this.connectViewModel.CanRefreshPorts();
                this.messageToolStripStatusLabel.Text = this.connectViewModel.ConnectionStatus;
            }
        }

        private void DisplayText(ListBox listBox, string value)
        {
            listBox.SuspendLayout();
            if (listBox.Items.Count > 10000)
            {
                for (int i = 0; i < 50; i++)
                {
                    listBox.Items.RemoveAt(0);
                }
            }

            listBox.Items.Add(value);
            listBox.SelectedIndex = listBox.Items.Count - 1;
            listBox.ResumeLayout();
        }

        private void AboutToolStripMenuItem_Click(object sender, EventArgs e)
        {
            using (Form about = new AboutBoxForm())
            {
                about.ShowDialog();
            }
        }

        private void ClearTransponderResponsesButton_Click(object sender, EventArgs e)
        {
            this.transponderListBox.Items.Clear();
        }

        private void ClearBarcodeResponsesButton_Click(object sender, EventArgs e)
        {
            this.barcodeListBox.Items.Clear();
        }

        private void ClearProtocolResponsesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.responsesListBox.Items.Clear();
        }

        private void ClearTransponderResponsesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.transponderListBox.Items.Clear();
        }

        private void ClearBarcodeResponsesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.barcodeListBox.Items.Clear();
        }

        private void ClearAllResponsesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            this.responsesListBox.Items.Clear();
            this.transponderListBox.Items.Clear();
            this.barcodeListBox.Items.Clear();
        }

        private void ShowProtocolResponsesToolStripMenuItem_Click(object sender, EventArgs e)
        {
            bool newState = !this.viewModel.IsProtocolResponseVisible;
            this.mainSplitContainer.Panel1Collapsed = !newState;
            this.showProtocolResponsesToolStripMenuItem.Checked = newState;
            this.viewModel.IsProtocolResponseVisible = newState;
        }

        private void QuerySessionComboBox_SelectedIndexChanged(object sender, EventArgs e)
        {
            this.viewModel.Session = (QuerySession)querySessionComboBox.SelectedItem;
        }

        private void QueryTargetComboBox_SelectedIndexChanged(object sender, EventArgs e)
        {
            this.viewModel.Target = (QueryTarget)queryTargetComboBox.SelectedItem;
        }        
    }
}
