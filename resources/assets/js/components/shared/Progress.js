const React = require('react')
import '../../../css/shared/Progress.css'

class Progress extends React.Component {
  render() {
    const marginPx = (this.props.margin || 0) + 'px'
    return (
      <div className="text-center" style={{width: this.props.size + 'px', height: this.props.size + 'px', margin: marginPx + ' auto'}}>
        {!this.props.inProgress
          ? <div></div>
          : <div className="spinner">
              <div className="double-bounce1"></div>
              <div className="double-bounce2"></div>
            </div>
        }
      </div>
    )
  }
}

module.exports = Progress