using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace AsciiProtocolInventory
{
    public interface IConnectionSettings
    {
        string PortName{get;set;}
    }

    public interface IDisplaySettings
    {
        bool IsProtocolResponseVisible { get; set; }
    }

    namespace Properties
    {
        partial class Settings
            :IConnectionSettings ,IDisplaySettings 
        {
        }
    }
}
