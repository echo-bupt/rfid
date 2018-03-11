using AsciiProtocolInventory.Services;
using AsciiProtocolInventory.ViewModels;
using AsciiProtocolInventory.Views;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Net;
using System.Net.Sockets;
using System.Text;
using System.Threading;
using System.Windows.Forms;

namespace AsciiProtocolInventory
{
    public class serviceSocket
    {
        Socket _socket = null;
        int BACKLOG = 10;
        IPEndPoint _endPoint;
        public Socket serverSocket;
        public ReaderService reader;
        public MainViewModel viewModels;
        private MainForm mainform;

        public serviceSocket(string ip, int port)
        {
            _socket = new Socket(AddressFamily.InterNetwork, SocketType.Stream, ProtocolType.Tcp);
            IPAddress _ip = IPAddress.Parse(ip);
            _endPoint = new IPEndPoint(_ip, port);
        }
        public void start(MainViewModel viewModel, ReaderService reader, MainForm mainform, CancellationToken ct)
        {
            this.viewModels = viewModel;
            this.reader = reader;
            this.mainform = mainform;
            _socket.Bind(_endPoint); //绑定端口
            _socket.Listen(BACKLOG); //开启监听
            //Thread acceptServer = new Thread(AcceptWork); //开启新线程处理监听
            //acceptServer.IsBackground = true;
            //acceptServer.Start();
            AcceptWork(ct);
        }

        public void AcceptWork(CancellationToken ct)
        {
            while (true)
            {
                try
                {
                    serverSocket = _socket.Accept();                   //用cSocket来代表该客户端连接
                    //serverSocket.ReceiveTimeout = 5;
                    if (serverSocket.Connected)                  //测试是否连接成功
                    {

                        //log("连接已建立!");
                        string recStr = "";
                        byte[] recByte = new byte[4096];
                        //这里会不会阻塞呢 会阻塞的、、假设 客户端不发生数据、
                        int bytes = serverSocket.Receive(recByte, recByte.Length, 0);
                        recStr += Encoding.ASCII.GetString(recByte, 0, bytes);
                        /*
                        //send message
                        Console.WriteLine("服务器端获得客户端发送信息:{0}", recStr);
                        String sendStr = "hello client!";
                        byte[] sendByte = Encoding.ASCII.GetBytes(sendStr);
                        serverSocket.Send(sendByte, sendByte.Length, 0);
                        Console.WriteLine("服务器端向客户端发送信息:{0}", sendStr);
                        serverSocket.Close();
                        */

                        //接收到 读取命令!
                        if (recStr == "read")
                        {
                            //Service.Instance.read("socket send excuteAsync");
                            this.viewModels.epc = "";
                            if (Service.Instance.noReadCount >= 2)
                            {
                                //log("noReadCount to exit!!");
                                byte[] sendByte = Encoding.ASCII.GetBytes("fail");
                                this.serverSocket.Send(sendByte, sendByte.Length, 0);
                                try
                                {
                                    this.mainform.cts.Cancel();
                                }
                                catch (AggregateException ex)
                                {

                                }
                                Thread.Sleep(200);
                                this.serverSocket.Close();
                                this.serverSocket = null;
                                ct.ThrowIfCancellationRequested();
                            }
                            Service.Instance.noReadCount++;
                            this.mainform.excutesync();
                        }
                        else if (recStr == "exit")
                        {
                            //log(recStr);
                            this.mainform.exit();
                        }

                    }
                    else
                    {
                        // MessageBox.Show("连接失败");
                        //log("连接失败!");
                    }
                }
                catch (SocketException ex)
                {
                   //log("socket异常:" + ex.Message);          //捕获Socket异常
                }
                catch (Exception es)
                {
                    //log("其它异常:" + es.Message);      //捕获其他异常
                }
            }
        }
        public void log(string message)
        {
            FileStream fs2 = new FileStream("log.txt", FileMode.Append, FileAccess.Write);
            StreamWriter sr2 = new StreamWriter(fs2);
            sr2.WriteLine(message);
            sr2.Close();
            fs2.Close();
        }
    }
}
