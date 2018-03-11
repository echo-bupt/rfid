//-----------------------------------------------------------------------
// <copyright file="MainForm.cs" company="Technology Solutions UK Ltd"> 
//     Copyright (c) 2014 Technology Solutions UK Ltd. All rights reserved. 
// </copyright> 
// <author>Robin Stone</author>
//-----------------------------------------------------------------------
namespace AsciiProtocolInventory.Views
{
    using Services;
    using System;
    using System.Collections.Generic;
    using System.ComponentModel;
    using System.Data;
    using System.Drawing;
    using System.Linq;
    using System.Text;
    using System.Threading;
    using System.Threading.Tasks;
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

        private ReaderService reader;

        public serviceSocket socket;
        public CancellationTokenSource cts;

        //delegate void SetTextCallBack(string text);

        /// <summary>
        /// Initializes a new instance of the MainForm class
        /// </summary>
        public MainForm()
        {
            this.reader = Service.Instance.reader;
            this.viewModel = Service.Instance.MainViewModel;
            this.socket = Service.Instance.socket;
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
            /*
            this.mybutton.Location = new System.Drawing.Point(90, 80);
            // mytestbox.Location = PointToClient(MousePosition);
            this.mybutton.Size = new Size(100, 50); //自己调整
            this.mybutton.Text = "连接中...";
            Button disConnect = new Button();
            disConnect.Location = new System.Drawing.Point(90, 150);
            // mytestbox.Location = PointToClient(MousePosition);
            disConnect.Size = new Size(100, 50); //自己调整
            disConnect.Text = "断开连接!";
            this.Controls.Add(mybutton); //添加，添到哪里自己调整
            this.Controls.Add(disConnect);
            disConnect.Click += new EventHandler(this.disConnect);
            */
            this.FormClosing += new FormClosingEventHandler(close);
            //Thread workConnect = new Thread(work);
            //workConnect.IsBackground = true;
            //workConnect.Start();
            //Task t1 = new Task(work);
            //t1.Start();
            cts = new CancellationTokenSource();
            var ct = cts.Token;
            Task task1 = new Task(() => { work(ct); }, ct);
            task1.Start();
        }

        public void work(CancellationToken ct)
        {
            bool agin = false;
            bool isconnectok = false;
            while (true)
            {
                //等待连接、、连接好后其实距离就够了、、这样不用循环去发送指令读取了 因为距离不够的
                //就卡在这了 能发送指令时已连接好 指令一定能发出来、
                //即点击扫描后 一直等待连接、连接好自然可发指令、、
                if (!agin)
                {
                    this.reader.Connect();
                }
                else
                {
                    this.reader.ConnectAgain();
                }
                if (this.reader.IsConnected)
                {
                    isconnectok = true;
                    try
                    {
                        this.viewModel.readTry();
                    }
                    catch (Exception ex)
                    {
                        //说明命令发送失败:
                        isconnectok = false;
                        agin = true;
                        String message = ex.Message;
                    }
                }
                if (isconnectok)
                {
                    //*******here...gaidong le danxiancheng 
                    //SetText("连接成功!");
                    break;
                }
                Thread.Sleep(50);
            }
            //连接成功后 开启socket
            this.socket.start(this.viewModel, this.reader, this,ct);
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
            this.resultsSplitContainer.Panel1MinSize = 300;
            this.resultsSplitContainer.Panel2MinSize = 200;
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
        public void disconnect()
        {
            this.connectViewModel.Disconnect();
        }
        public void exit()
        {
            //Service.Instance.log("exit!!!");
            //Dispose();

            //this.reader.Disconnect();

            //Dispose();

            //Application.Exit();

            System.Environment.Exit(0);
        }
        public void disConnect(object sender, EventArgs s)
        {
            //Dispose();

            //this.reader.Disconnect();

            //Dispose();

           // Application.Exit();

            System.Environment.Exit(0);
        }
        /*
        private void SetText(string text)
        {
            if (this.mybutton.InvokeRequired)
            {
                SetTextCallBack stcb = new SetTextCallBack(SetText);
                this.Invoke(stcb, new object[] { text });
            }
            else
            {
                this.mybutton.Text = text;
            }
        }
        */
        private void close(object sender, FormClosingEventArgs e)

        {
            //Dispose();

            //this.reader.Disconnect();

            //Dispose();

            //Application.Exit();

            System.Environment.Exit(0);

        }
        public void excuteAsync()
        {
                System.Threading.ThreadPool.QueueUserWorkItem(delegate (object state)
                {
                // don't block the UI while the command executes

                this.viewModel.ExecuteInventoryCommandAsynchronously();


                });
        }
        public void excutesync()
        {
            System.Threading.ThreadPool.QueueUserWorkItem(delegate (object state)
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
